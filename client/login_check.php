<?php
error_reporting(0);
session_start();

$host="localhost";
$user="root";
$password = "";
$db="carshop";

$data=mysqli_connect($host,$user,$password,$db);

if($data == false){
    die("connect error");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Câu truy vấn chỉ cần lấy id và usertype
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    
    $stmt = $data->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($row = $result->fetch_assoc()) {
        // Chỉ lưu các thông tin cần thiết vào session
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['usertype'] = $row['usertype'];

        if($row["usertype"] == 'user') {
            header("location:home.php");
        } else if($row["usertype"] == 'admin') {
            header("Location: /admin/ad_home.php");
        }
    } else {
        $_SESSION['loginMessage'] = "Email và Password không tồn tại!";
        header("Location: login.php");
    }
}
?>