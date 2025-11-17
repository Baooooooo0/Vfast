<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';
$config = require realpath(__DIR__ . '/../config/mail_config.php');

$logPath = __DIR__ . '/send_invoice_log.txt';
file_put_contents($logPath, date('Y-m-d H:i:s') . " 🧩 send_invoice.php loaded\n", FILE_APPEND);

if (!is_array($config) || empty($config['username'])) {
    file_put_contents($logPath, date('Y-m-d H:i:s') . " ❌ mail_config.php missing or invalid\n", FILE_APPEND);
    return false;
}

function send_invoice_for_order(string $order_id, mysqli $conn): bool {
    global $config, $logPath; // ✅ thêm dòng này để dùng $config bên trong

    $sql = "
      SELECT t.order_id, t.deposit AS amount, t.transaction_date,
             t.receiver_name, t.receiver_phone, t.receiver_address,
             t.product_id, u.email, u.name AS fullname,
             p.product_name
      FROM transactions t
      LEFT JOIN users   u ON u.id = t.customer_id
      LEFT JOIN product p ON p.product_id = t.product_id
      WHERE t.order_id = ?
      LIMIT 1
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $order_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if (!$row) {
        file_put_contents($logPath, date('Y-m-d H:i:s') . " ❌ Không tìm thấy đơn hàng $order_id\n", FILE_APPEND);
        return false;
    }
    if (empty($row['email'])) {
        file_put_contents($logPath, date('Y-m-d H:i:s') . " ⚠️ Đơn hàng $order_id không có email khách hàng\n", FILE_APPEND);
        return false;
    }

    $html = '
      <div style="font-family:Arial,sans-serif;max-width:600px;margin:auto">
        <h2>🧾 HÓA ĐƠN THANH TOÁN</h2>
        <p><b>Mã đơn hàng:</b> '.htmlspecialchars($row['order_id']).'</p>
        <p><b>Sản phẩm:</b> '.htmlspecialchars($row['product_name']).'</p>
        <p><b>Số tiền:</b> '.number_format((int)$row['amount'],0,',','.').' VND</p>
        <p><b>Ngày thanh toán:</b> '.date('d/m/Y H:i', strtotime($row['transaction_date'] ?? 'now')).'</p>
        <hr>
        <p><b>Khách hàng:</b> '.htmlspecialchars($row['receiver_name'] ?? $row['fullname'] ?? 'Khách hàng').'</p>
        <p><b>SĐT:</b> '.htmlspecialchars($row['receiver_phone'] ?? '').'</p>
        <p><b>Địa chỉ:</b> '.htmlspecialchars($row['receiver_address'] ?? '').'</p>
      </div>
    ';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $config['smtp_host'];
        $mail->Port       = $config['smtp_port'];
        $mail->SMTPSecure = $config['smtp_secure'];
        $mail->SMTPAuth   = $config['smtp_auth'];
        $mail->Username   = $config['username'];
        $mail->Password   = $config['password'];
        $mail->CharSet = 'UTF-8';

        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($row['email'], $row['receiver_name']);
        $mail->Subject = 'Hóa đơn đơn hàng ' . $order_id;
        $mail->isHTML(true);
        $mail->Body = $html;
        $mail->AltBody = strip_tags($html);
        $mail->send();

        file_put_contents($logPath, date('Y-m-d H:i:s') . " ✅ Mail sent to {$row['email']} for {$order_id}\n", FILE_APPEND);
        return true;
    } catch (Exception $e) {
        file_put_contents($logPath, date('Y-m-d H:i:s') . " ❌ Mail failed for {$order_id}: {$e->getMessage()}\n", FILE_APPEND);
        return false;
    }
}
