<?php
// sepay_webhook.php (PDO)
header('Content-Type: application/json; charset=utf-8');

// (Tuỳ chọn) xác thực header nếu bạn bật API Key trong SePay
// $apiKey = 'your-sepay-api-key-or-secret';
// if (($_SERVER['HTTP_AUTHORIZATION'] ?? '') !== 'Apikey '.$apiKey) {
//   http_response_code(401); echo json_encode(['success'=>false,'msg'=>'Unauthorized']); exit;
// }

$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) { echo json_encode(['success'=>false,'msg'=>'no data']); exit; }

// Lấy order_id từ nội dung chuyển khoản (content/description)
$content = $data['content'] ?? $data['description'] ?? '';
if (!preg_match('/ORD-[A-Za-z0-9\-]+/', $content, $m)) {
  echo json_encode(['success'=>false,'msg'=>'order_id not found']); exit;
}
$order_id = $m[0];

// DB
$pdo = new PDO('mysql:host=localhost;dbname=carshop;charset=utf8mb4', 'root', '', [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
]);

// Lấy giao dịch pending theo order_id
$stmt = $pdo->prepare("SELECT id, request_id, transaction_status FROM transactions WHERE order_id = :oid LIMIT 1");
$stmt->execute([':oid' => $order_id]);
$tx = $stmt->fetch();
if (!$tx) { echo json_encode(['success'=>false,'msg'=>'tx not found']); exit; }
if ($tx['transaction_status'] === 'completed') { echo json_encode(['success'=>true,'msg'=>'already completed']); exit; }

// Lấy số lượng thật từ request_id (đã lưu ở bước tạo)
$real_qty = (int)($tx['request_id'] ?? 0);
if ($real_qty <= 0) { echo json_encode(['success'=>false,'msg'=>'invalid qty']); exit; }

// UPDATE: set completed + đưa transaction_number về số lượng thật
$stmt = $pdo->prepare("
  UPDATE transactions
  SET transaction_status = 'completed',
      transaction_number = :q
  WHERE id = :id
");
$stmt->execute([':q' => $real_qty, ':id' => $tx['id']]);

echo json_encode(['success'=>true]);
