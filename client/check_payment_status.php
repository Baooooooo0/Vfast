<?php
// ==============================================
// check_payment_status.php - Kiểm tra trạng thái đơn hàng
// ==============================================
header('Content-Type: application/json; charset=utf-8');
require_once realpath(__DIR__ . '/../config/db_connect.php');

// 1️⃣ Nhận order_id từ AJAX
$order_id = $_POST['order_id'] ?? '';

if (empty($order_id)) {
    echo json_encode(["error" => "Missing order_id"]);
    exit;
}

// 2️⃣ Truy vấn DB để lấy trạng thái
$stmt = $conn->prepare("SELECT transaction_status FROM transactions WHERE order_id = ?");
$stmt->bind_param('s', $order_id);
$stmt->execute();
$result = $stmt->get_result();

// 3️⃣ Kiểm tra kết quả và phản hồi
if ($row = $result->fetch_assoc()) {
    $status = strtolower(trim($row['transaction_status']));

    // ✅ Trả kết quả JSON cho front-end
    if ($status === 'paid') {
        echo json_encode([
            "order_id" => $order_id,
            "payment_status" => "Paid",
            "message" => "Payment has been successfully verified"
        ]);
    } else {
        echo json_encode([
            "order_id" => $order_id,
            "payment_status" => "Pending",
            "message" => "Awaiting payment confirmation"
        ]);
    }
} else {
    echo json_encode([
        "order_id" => $order_id,
        "payment_status" => "NotFound",
        "message" => "Order ID not found in database"
    ]);
}

$stmt->close();
$conn->close();
?>
