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
    $new_status = $_POST["new_status"];
    $transaction_id = $_POST["transaction_id"];
    // echo $transaction_id;
    // echo $status;
    //Chỉ cho phép các trạng thái mà mình định nghĩa
    $allowed_statuses = ['Pending', 'Completed', 'Failed'];
    if(isset($transaction_id) && in_array($new_status, $allowed_statuses)){
        $sql = "UPDATE transactions SET transaction_status= ? WHERE transaction_id= ?";
        $stmt = mysqli_prepare($data, $sql);
        echo '<scrip>thành công</scrip>';
        if ($stmt) {
            // "si" nghĩa là tham số đầu tiên là String (s), thứ hai là Integer (i)
            mysqli_stmt_bind_param($stmt, "si", $new_status, $transaction_id);
            
            // 6. THỰC THI CẬP NHẬT
            mysqli_stmt_execute($stmt);
            
            mysqli_stmt_close($stmt);
            
            // 7. CHUYỂN HƯỚNG NGƯỜI DÙNG TRỞ LẠI
            // Chuyển về trang admin, và trỏ thẳng đến tab 'transactions'
            // Reload the transactions page in the browser
            echo '<script>location.replace("ad_home.php");</script>';
            exit(); // Dừng script ngay sau khi chuyển hướng
            
        } else {
            echo "Lỗi: Không thể chuẩn bị câu lệnh SQL. " . mysqli_error($data);
        }
    }
    else {
    // Nếu ai đó cố gắng truy cập file này trực tiếp, chuyển họ về trang chủ
    header("Location: ad_home.php");
    exit();
}
}

mysqli_close($data);
?>
