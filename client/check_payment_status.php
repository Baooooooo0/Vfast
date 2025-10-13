<?php
// check_payment_status.php
header('Content-Type: application/json; charset=utf-8');
$order_id = $_POST['order_id'] ?? '';
if (!$order_id) { echo json_encode(['payment_status'=>'order_not_found']); exit; }

$mysqli = new mysqli('localhost','root','','carshop');
if ($mysqli->connect_error) { echo json_encode(['payment_status'=>'db_error']); exit; }
$mysqli->set_charset('utf8mb4');

// Tìm giao dịch theo order_id
$stmt = $mysqli->prepare("SELECT transaction_status FROM transactions WHERE order_id = ? LIMIT 1");
$stmt->bind_param('s', $order_id);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
  echo json_encode(['payment_status' => ($row['transaction_status']==='completed' ? 'Paid' : 'Unpaid')]);
} else {
  echo json_encode(['payment_status'=>'order_not_found']);
}
