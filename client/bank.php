<?php
// bank.php (PDO version)
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: oto.php'); exit; }

// CẤU HÌNH
$BANK_ACCOUNT_NUMBER = '1029780778';
$BANK_CODE           = 'vcb';
$QR_TEMPLATE         = 'compact';

// INPUT
$user_id        = (int)$_SESSION['user_id'];
$product_id     = trim($_POST['product_id'] ?? '');
$product_name   = trim($_POST['product_name'] ?? '');
$product_price  = (int)($_POST['product_price'] ?? 0);
$quantity       = (int)($_POST['quantity'] ?? 1);
$payment_type   = $_POST['payment_type'] ?? 'full';
$amount         = (int)($_POST['amount'] ?? 0);

$receiver_name  = trim($_POST['receiver_name'] ?? '');
$receiver_phone = trim($_POST['receiver_phone'] ?? '');
$receiver_addr  = trim($_POST['receiver_address'] ?? '');

// VALIDATE
if ($amount <= 0 || $product_id === '' || $product_name === '') {
  http_response_code(400);
  echo "Yêu cầu không hợp lệ."; exit;
}

// DB (PDO)
$pdo = new PDO('mysql:host=localhost;dbname=carshop;charset=utf8mb4', 'root', '', [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
]);

// ORDER
$order_id      = 'ORD-'.$product_id.'-U'.$user_id.'-'.time();
$today         = date('Y-m-d');
$deposit       = ($payment_type === 'deposit') ? $amount : 0;

// Lưu giao dịch pending:
// - transaction_number = 0 (CHƯA trừ kho)
// - request_id = số lượng thật (để webhook dùng trừ sau)
$sql = "
INSERT INTO transactions
  (product_id, customer_id, transaction_date, deposit, transaction_number,
   payment_method, transaction_status, order_id, request_id, momo_trans_id,
   receiver_name, receiver_phone, receiver_address)
VALUES
  (:product_id, :customer_id, :transaction_date, :deposit, :transaction_number,
   'Chuyển khoản ngân hàng', 'pending', :order_id, :request_id, NULL,
   :receiver_name, :receiver_phone, :receiver_address)
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':product_id'         => $product_id,
  ':customer_id'        => $user_id,
  ':transaction_date'   => $today,
  ':deposit'            => $deposit,
  ':transaction_number' => 0,               // <<== CHỐT: CHƯA trừ kho
  ':order_id'           => $order_id,
  ':request_id'         => (string)$quantity, // <<== LƯU SL THẬT TẠM Ở ĐÂY
  ':receiver_name'      => $receiver_name,
  ':receiver_phone'     => $receiver_phone,
  ':receiver_address'   => $receiver_addr,
]);

// QR VietQR
$transfer_desc = $order_id;
$qr_url = 'https://qr.sepay.vn/img?' . http_build_query([
  'acc'      => $BANK_ACCOUNT_NUMBER,
  'bank'     => $BANK_CODE,
  'amount'   => $amount,
  'des'      => $transfer_desc,
  'template' => $QR_TEMPLATE,
]);
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <title>Thanh toán chuyển khoản</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php include('home_css.php'); ?>
  <style>
    .wrap{max-width:800px;margin:120px auto;padding:24px;background:#fff;border-radius:10px;box-shadow:0 4px 20px rgba(0,0,0,.08)}
    .row{display:flex;gap:24px;align-items:center;flex-wrap:wrap}
    .qr img{max-width:260px;border-radius:8px}
    .info table{width:100%}
    .info th{text-align:left;width:40%}
    .badge{display:inline-block;padding:2px 8px;border-radius:6px;background:#eee}
    .muted{color:#666}
    .cta{margin-top:16px}
    code{background:#f4f4f4;padding:2px 6px;border-radius:4px}
  </style>
</head>
<body>
<?php include('header.php'); ?>
<div class="wrap">
  <h2>Chuyển khoản ngân hàng</h2>
  <p class="muted">Quét QR hoặc chuyển khoản đúng số tiền & nội dung để hệ thống tự xác nhận.</p>

  <div class="row">
    <div class="qr">
      <img src="<?php echo htmlspecialchars($qr_url); ?>" alt="QR chuyển khoản">
    </div>
    <div class="info">
      <table>
        <tr><th>Ngân hàng</th><td><span class="badge"><?php echo strtoupper(htmlspecialchars($BANK_CODE)); ?></span></td></tr>
        <tr><th>Số tài khoản</th><td><strong><?php echo htmlspecialchars($BANK_ACCOUNT_NUMBER); ?></strong></td></tr>
        <tr><th>Số tiền</th><td><strong><?php echo number_format($amount,0,',','.'); ?> VND</strong></td></tr>
        <tr><th>Nội dung</th><td><code><?php echo htmlspecialchars($transfer_desc); ?></code></td></tr>
        <tr><th>Đơn hàng</th><td><?php echo htmlspecialchars($order_id); ?></td></tr>
      </table>
      <div class="cta">
        <button class="btn btn-secondary" onclick="location.href='oto.php'">Chọn sản phẩm khác</button>
      </div>
    </div>
  </div>

  <hr>
  <div id="statusBox" class="muted">Đang chờ thanh toán...</div>
</div>
<?php include('footer.php'); ?>
<script>
let paid = false;
const orderId = <?php echo json_encode($order_id); ?>;
function checkStatus(){
  if (paid) return;
  fetch('check_payment_status.php', {
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body: new URLSearchParams({order_id: orderId})
  }).then(r=>r.json()).then(res=>{
    if (res.payment_status === 'Paid') {
      paid = true;
      document.getElementById('statusBox').innerHTML = '<span class="badge">ĐÃ NHẬN THANH TOÁN ✅</span>';
    }
  }).catch(()=>{});
}
setInterval(checkStatus, 2000);
</script>
</body>
</html>
