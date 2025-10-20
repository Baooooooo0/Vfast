<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once realpath(__DIR__ . '/../config/db_connect.php');

// ====== Cấu hình ======
$BANK_ACCOUNT_NUMBER = '0886219143';
$BANK_CODE = 'tpb'; // TPBank
$template = 'compact';

// ====== Dữ liệu thanh toán ======
$user_id = (int)$_SESSION['user_id'];
$product_id = trim($_POST['product_id'] ?? '');
$product_name = trim($_POST['product_name'] ?? '');
$product_price = (int)($_POST['product_price'] ?? 0);
$quantity = (int)($_POST['quantity'] ?? 1);
$payment_type = $_POST['payment_type'] ?? 'full';
$amount = (int)($_POST['amount'] ?? $product_price);
$order_id = 'ORD' . $product_id . 'U' . $user_id . time(); // ✅ khớp với webhook

// ====== Lấy thông tin giao hàng ======
$receiver_name = $receiver_phone = $receiver_address = null;
$sql_user = "SELECT receiver_name, receiver_phone, receiver_address FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param('i', $user_id);
$stmt_user->execute();
$res_user = $stmt_user->get_result();
if ($res_user->num_rows > 0) {
    $user = $res_user->fetch_assoc();
    $receiver_name = $user['receiver_name'];
    $receiver_phone = $user['receiver_phone'];
    $receiver_address = $user['receiver_address'];
}
$stmt_user->close();

// ====== Tạo đơn hàng PENDING ======
$sql = "
INSERT INTO transactions
(product_id, customer_id, transaction_date, deposit, transaction_number,
 payment_method, transaction_status, order_id, request_id,
 momo_trans_id, receiver_name, receiver_phone, receiver_address)
VALUES (?, ?, NOW(), ?, 0, 'Chuyển khoản ngân hàng', 'pending', ?, 1, NULL, ?, ?, ?)
";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    'siissss',
    $product_id,
    $user_id,
    $amount,
    $order_id,
    $receiver_name,
    $receiver_phone,
    $receiver_address
);
$stmt->execute();
$stmt->close();

// ====== Tạo QR SePay ======
$transfer_desc = $order_id;
$qr_url = 'https://qr.sepay.vn/img?' . http_build_query([
    'acc' => $BANK_ACCOUNT_NUMBER,
    'bank' => $BANK_CODE,
    'amount' => $amount,
    'des' => $transfer_desc,
    'template' => $template
]);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán chuyển khoản</title>
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
<div class="wrap">
  <h2>Chuyển khoản ngân hàng</h2>
  <p class="muted">Quét QR hoặc chuyển khoản đúng nội dung để hệ thống tự động xác nhận.</p>

  <div class="row">
    <div class="qr">
      <img src="<?= htmlspecialchars($qr_url) ?>" alt="QR chuyển khoản">
    </div>
    <div class="info">
      <table>
        <tr><th>Ngân hàng</th><td><span class="badge"><?= strtoupper($BANK_CODE) ?></span></td></tr>
        <tr><th>Số tài khoản</th><td><strong><?= htmlspecialchars($BANK_ACCOUNT_NUMBER) ?></strong></td></tr>
        <tr><th>Số tiền</th><td><strong><?= number_format($amount, 0, ',', '.') ?> VND</strong></td></tr>
        <tr><th>Nội dung</th><td><code><?= htmlspecialchars($transfer_desc) ?></code></td></tr>
        <tr><th>Đơn hàng</th><td><?= htmlspecialchars($order_id) ?></td></tr>
      </table>
    </div>
  </div>

  <hr>
  <div id="statusBox" class="muted">Đang chờ thanh toán...</div>
</div>

<script>
let paid = false;
const orderId = <?= json_encode($order_id) ?>;
function checkStatus() {
    if (paid) return;
    fetch('check_payment_status.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({order_id: orderId})
    })
    .then(r => r.json())
    .then(res => {
        console.log(res);
        if (res.payment_status === 'Paid') {
            paid = true;
            document.getElementById('statusBox').innerHTML = '<span class="badge">✅ ĐÃ NHẬN THANH TOÁN</span>';
        }
    })
    .catch(err => console.error(err));
}
setInterval(checkStatus, 3000);
</script>
</body>
</html>
