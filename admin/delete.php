<?php

$host = 'localhost';
$user = 'root';
$password = '';
$db='carshop';


$data = mysqli_connect($host,$user,$password,$db);

if (!$data) {
    die("Connection failed: " . mysqli_connect_error());
}
if(isset($_GET['transaction_id'])){
    $code = $_GET['transaction_id'];
    
    //bat dau transaction 
    mysqli_begin_transaction($data);
    
    //xóa bảng transactions
    $stmt_transactions = mysqli_prepare($data,"DELETE FROM transactions where transaction_id = ?");
    mysqli_stmt_bind_param($stmt_transactions , 'i' , $code);
    $result_transactions = mysqli_stmt_execute($stmt_transactions);
    
    if( $result_transactions ){
        mysqli_commit($data);
        header("Location:../admin/ad_home.php");
        exit();
    }
    else{
        mysqli_rollback($data);
        echo "Lỗi khi xóa dữ liệu ";
    }
    // Đóng câu lệnh prepared statement
    mysqli_stmt_close($stmt_transactions);
    
}elseif(isset($_GET['user_id'])){
    $user_id = $_GET['user_id'];
    
    $stmt_user = mysqli_prepare($data,"DELETE FROM users where id = ?");
    mysqli_stmt_bind_param($stmt_user , 'i' , $user_id);
    $result_user = mysqli_stmt_execute($stmt_user);

    if( $result_user ){
        header("Location:../admin/ad_home.php");
        exit();
    }
    else{
        echo "Lỗi khi xóa dữ liệu ";
    }
}
else{
    echo "Không tìm thấy mã giao dịch phù hợp !";
}

?>