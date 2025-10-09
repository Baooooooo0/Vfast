<?php
error_reporting(0);
session_start();

//$_SERVER: Chứa thông tin về máy chủ và môi trường thực thi.
//$_POST: Chứa dữ liệu được gửi từ các form HTML sử dụng phương thức POST.
//$_SESSION: Được sử dụng để lưu trữ dữ liệu phiên trên máy chủ, duy trì trạng thái người dùng trong suốt phiên làm việc.
$host="localhost";
$user="root";
$password = "";
$db="carshop";

$data=mysqli_connect($host,$user,$password,$db);

if($data == false){
    die("connect error");
    
}
    if($_SERVER["REQUEST_METHOD"] == "POST")  
    {
        $email=$_POST['email'];
        $password=$_POST['password'];

        $sql="select * from users 
               where email = '".$email."' AND password = '".$password."'  ";


        $result = mysqli_query($data,$sql);
        
        $row = mysqli_fetch_array($result);

        if($row["usertype"]== 'user'){
            $_SESSION['email'] = $email;
            $_SESSION['usertype'] = 'user';
            header("location:home.php");
        }
        else if($row["usertype"] == 'admin'){
            $_SESSION['email'] = $email;
            $_SESSION['usertype'] = 'admin';
            header("Location: /admin/ad_home.php");
        }
        else
        {
            $_SESSION['loginMessage'] = "Email và Password không tồn tại!";
            header("Location: login.php");
        }
    }


?>