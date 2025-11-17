<?php
session_start();
header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Bạn cần đăng nhập để thực hiện thao tác này.'
    ]);
    exit;
}

// Kiểm tra method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức không hợp lệ.'
    ]);
    exit;
}

// Lấy transaction_id từ POST
$transaction_id = $_POST['transaction_id'] ?? '';
$user_id = $_SESSION['user_id'];

if (empty($transaction_id)) {
    echo json_encode([
        'success' => false,
        'message' => 'Mã đơn hàng không hợp lệ.'
    ]);
    exit;
}

// Kết nối database
require_once __DIR__ . '/../config/db_connect.php';

try {
    // Kiểm tra đơn hàng có tồn tại và thuộc về user hiện tại không
    $check_sql = "SELECT transaction_id, transaction_status, product_id FROM transactions WHERE transaction_id = ? AND customer_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, 'si', $transaction_id, $user_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);
    $order = mysqli_fetch_assoc($result);
    
    if (!$order) {
        echo json_encode([
            'success' => false,
            'message' => 'Đơn hàng không tồn tại hoặc không thuộc về bạn.'
        ]);
        exit;
    }
    
    // Kiểm tra trạng thái đơn hàng có thể hủy không
    if ($order['transaction_status'] === 'cancelled') {
        echo json_encode([
            'success' => false,
            'message' => 'Đơn hàng đã được hủy trước đó.'
        ]);
        exit;
    }
    
    // Cập nhật trạng thái đơn hàng thành 'cancelled'
    $update_sql = "UPDATE transactions SET transaction_status = 'cancelled' WHERE transaction_id = ? AND customer_id = ?";
    $update_stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($update_stmt, 'si', $transaction_id, $user_id);
    
    if (mysqli_stmt_execute($update_stmt)) {
        // Cập nhật lại số lượng sản phẩm trong kho (tăng lại)
        $product_sql = "UPDATE product SET product_number = product_number + 1 WHERE product_id = ?";
        $product_stmt = mysqli_prepare($conn, $product_sql);
        mysqli_stmt_bind_param($product_stmt, 's', $order['product_id']);
        mysqli_stmt_execute($product_stmt);
        
        echo json_encode([
            'success' => true,
            'message' => 'Đơn hàng đã được hủy thành công.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi hủy đơn hàng. Vui lòng thử lại.'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi hệ thống: ' . $e->getMessage()
    ]);
} finally {
    // Đóng kết nối
    if (isset($conn)) {
        mysqli_close($conn);
    }
}
?>