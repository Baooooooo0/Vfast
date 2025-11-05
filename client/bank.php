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
const orderId = "<?php echo $order_id ?? ''; ?>";

function pollPayment() {
  if (!orderId) return;
  fetch(`check_payment_status.php?order_id=${orderId}`)
    .then(res => res.json())
    .then(data => {
      console.log("Trạng thái thanh toán:", data);
      if (data.status === "completed" || data.payment_status === "Paid") {
  alert("✅ Thanh toán thành công!");

  // Hiển thị hóa đơn trực tiếp trên trang
  const invoiceHTML = `
    <div class="invoice" style="margin-top:30px;padding:20px;border:1px solid #ccc;border-radius:8px;">
      <h3>🧾 HÓA ĐƠN THANH TOÁN</h3>
      <p><strong>Mã đơn hàng:</strong> ${orderId}</p>
      <p><strong>Sản phẩm:</strong> <?= htmlspecialchars($product_name) ?></p>
      <p><strong>Số lượng:</strong> <?= htmlspecialchars($quantity) ?></p>
      <p><strong>Số tiền:</strong> <?= number_format($amount, 0, ',', '.') ?> VND</p>
      <p><strong>Ngày thanh toán:</strong> ${new Date().toLocaleString('vi-VN')}</p>
      <hr>
      <p><strong>Khách hàng:</strong> <?= htmlspecialchars($receiver_name) ?></p>
      <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($receiver_phone) ?></p>
      <p><strong>Địa chỉ giao hàng:</strong> <?= htmlspecialchars($receiver_address) ?></p>
      <button onclick="window.print()" style="margin-top:10px;padding:8px 16px;background:#007bff;color:white;border:none;border-radius:5px;cursor:pointer;">
        🖨️ In hóa đơn
      </button>
    </div>
  `;

  // Cập nhật vùng statusBox bằng hóa đơn
  const box = document.getElementById('statusBox');
  box.innerHTML = invoiceHTML;
  box.classList.add('status-success');
}
else if (data.status === "pending" || data.status === "paid") {
        setTimeout(pollPayment, 4000);
      } else {
        console.log("⏳ Đang chờ thanh toán...");
        setTimeout(pollPayment, 4000);
      }
    })
    .catch(err => {
      console.error("Lỗi khi kiểm tra thanh toán:", err);
      setTimeout(pollPayment, 4000);
    });
}

// bắt đầu kiểm tra sau 5 giây, lặp mỗi 4s
setTimeout(pollPayment, 5000);
</script>

</body>
</html>
