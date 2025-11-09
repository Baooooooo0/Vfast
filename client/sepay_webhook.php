<?php
header('Content-Type: application/json');
require_once realpath(__DIR__ . '/../config/db_connect.php');

// ✅ Lấy header xác thực
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';
$expectedKey = 'E1Y2KPJFTM1WPJGCUBLI8TDVEIWKVPAQPVNHB4FNXAVC3Q7WHSZEUL03XSBBHOCF';

// Ghi log header để debug
file_put_contents(__DIR__ . '/sepay_webhook_log.txt',
    date('Y-m-d H:i:s') . " 🔔 HEADER: $authHeader" . PHP_EOL, FILE_APPEND);

// Kiểm tra key
if (stripos($authHeader, $expectedKey) === false) {
    http_response_code(401);
    echo json_encode(["error" => "Invalid Authorization"]);
    exit;
}

// ✅ Đọc dữ liệu JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);
file_put_contents(__DIR__ . '/sepay_webhook_log.txt',
    date('Y-m-d H:i:s') . " 🔔 BODY: $input" . PHP_EOL, FILE_APPEND);

// ✅ Thêm dòng này để tránh lỗi undefined variable
$status = strtolower($data['status'] ?? 'completed');

// Trích xuất order_id
$content = $data['content'] ?? '';
preg_match('/ORD[-A-Z0-9]+/i', $content, $matches);
$order_id = $matches[0] ?? '';

if (!$order_id) {
    file_put_contents(__DIR__ . '/sepay_webhook_log.txt', "⚠️ Không tìm thấy order_id trong content: $content" . PHP_EOL, FILE_APPEND);
    echo json_encode(["error" => "Order not found in content"]);
    exit;
}

// ✅ Cập nhật DB
$stmt = $conn->prepare("UPDATE transactions SET transaction_status = 'completed' WHERE order_id = ?");
$stmt->bind_param('s', $order_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    file_put_contents(__DIR__ . '/sepay_webhook_log.txt', "✅ Cập nhật DB thành công cho $order_id" . PHP_EOL, FILE_APPEND);
    @include_once __DIR__ . '/send_invoice.php';
    if (function_exists('send_invoice_for_order')) { send_invoice_for_order($order_id, $conn); }
    echo json_encode(["success" => true, "message" => "Updated $order_id to paid"]);
} else {
    file_put_contents(__DIR__ . '/sepay_webhook_log.txt', "⚠️ Không tìm thấy đơn hàng $order_id trong DB" . PHP_EOL, FILE_APPEND);
    echo json_encode(["warning" => "Order not found"]);
}

// Sau khi cập nhật transaction_status = 'completed'
if ($status === 'completed') {
    // ✅ Trừ tồn kho: lấy product_id từ transaction
    $q = $conn->prepare("SELECT product_id FROM transactions WHERE order_id = ?");
    $q->bind_param('s', $order_id);
    $q->execute();
    $res = $q->get_result()->fetch_assoc();

    if ($res) {
        $product_id = $res['product_id'];
        $qty = 1; // số lượng mỗi đơn mặc định = 1

        // ✅ Giảm tồn kho đúng bảng + cột
        $updateStock = $conn->prepare("UPDATE product SET product_number = GREATEST(product_number - ?, 0) WHERE product_id = ?");
        $updateStock->bind_param('is', $qty, $product_id);
        $updateStock->execute();

        file_put_contents(__DIR__ . '/sepay_webhook_log.txt', "✅ Giảm tồn kho cho $product_id (-$qty)" . PHP_EOL, FILE_APPEND);
    }

    echo json_encode(["success" => true, "message" => "Order updated and stock decreased"]);
    exit;
}
?>
