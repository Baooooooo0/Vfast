<?php
require_once realpath(__DIR__ . '/../config/db_connect.php');

header('Content-Type: application/json');

$order_id = $_GET['order_id'] ?? '';
if (!$order_id) {
    echo json_encode(["error" => "Missing order_id"]);
    exit;
}

$stmt = $conn->prepare("SELECT transaction_status FROM transactions WHERE order_id = ?");
$stmt->bind_param('s', $order_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if ($res) {
    echo json_encode(["status" => $res['transaction_status']]);
} else {
    echo json_encode(["status" => "not_found"]);
}
?>
