<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";

$data = mysqli_connect($host, $user, $password, $db);

if (!$data) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $transaction_id = $_POST['transaction_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $pob = $_POST['pob'];
    $product_name = $_POST['product_name'];
    $color = $_POST['color'];
    
    // Loại bỏ dấu phẩy khỏi chuỗi số tiền trước khi lưu vào DB
    $price = str_replace(',', '', $_POST['price']);
    $deposit = str_replace(',', '', $_POST['deposit']);
    $number = $_POST['number'];

    // Bắt đầu giao dịch
    mysqli_begin_transaction($data);

    try {
        // Cập nhật bảng users
        $update_users_query = "
            UPDATE users
            INNER JOIN transactions ON transactions.customer_id = users.id
            SET 
                users.name = ?, 
                users.phone = ?, 
                users.pob = ?
            WHERE 
                transactions.transaction_id = ?";
        
        $stmt_users = mysqli_prepare($data, $update_users_query);
        mysqli_stmt_bind_param($stmt_users, 'sssi', $name, $phone, $pob, $transaction_id);
        mysqli_stmt_execute($stmt_users);

        // Cập nhật bảng product
        $update_product_query = "
            UPDATE product
            INNER JOIN transactions ON transactions.product_id = product.product_id
            SET 
                product.product_name = ?, 
                product.color = ?, 
                product.product_price = ?
            WHERE 
                transactions.transaction_id = ?";
        
        $stmt_product = mysqli_prepare($data, $update_product_query);
        mysqli_stmt_bind_param($stmt_product, 'sssi', $product_name, $color, $price, $transaction_id);
        mysqli_stmt_execute($stmt_product);

        // Cập nhật bảng transactions
        $update_transactions_query = "
            UPDATE transactions
            SET 
                transactions.deposit = ?, 
                transactions.transaction_number = ?
            WHERE 
                transactions.transaction_id = ?";
        
        $stmt_transactions = mysqli_prepare($data, $update_transactions_query);
        mysqli_stmt_bind_param($stmt_transactions, 'iii', $deposit, $number, $transaction_id);
        mysqli_stmt_execute($stmt_transactions);

        // Xác nhận giao dịch
        mysqli_commit($data);

        echo "<script>alert('Cập nhật thông tin thành công.'); window.location.href='../admin/ad_home.php';</script>";

    } catch (Exception $e) {
        // Quay lại khi có lỗi
        mysqli_rollback($data);
        echo "<script>alert('Cập nhật thông tin thất bại.'); window.location.href='../admin/ad_home.php';</script>";
    }

    mysqli_stmt_close($stmt_users);
    mysqli_stmt_close($stmt_product);
    mysqli_stmt_close($stmt_transactions);
}

mysqli_close($data);
?>
