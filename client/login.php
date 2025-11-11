<?php 
    error_reporting(0);
    session_start();
    // session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="../css/login.css">
    <?php include('home_css.php') ?>
</head>
<body>
   
    <div class="login-container">
        <i class="fas fa-user-circle" ></i>
        <h2>Đăng nhập</h2>
        <h4>
            <?php 
            if (!empty($_SESSION['loginMessage'])) {
                echo $_SESSION['loginMessage'];
                unset($_SESSION['loginMessage']);
            }
            if (!empty($_SESSION['registerSuccess'])) {
                echo '<span style="color: green;">' . $_SESSION['registerSuccess'] . '</span>';
                unset($_SESSION['registerSuccess']);
            }
            ?>    
        </h4>
        <form action="login_check.php" method="POST">
            <input type="email" placeholder="Email" id="email" name="email" required>
            <input type="password" placeholder="Mật khẩu" id="password" name="password" required>
            
            <div class="forget-password"><a href="#">Quên mật khẩu?</a></div>
                
            
            <button type="submit" name="submit" >Đăng Nhập</button>
            <span>Chưa có tài khoản?</span>
            <button class="register" type="button" onclick="window.location.href='register.php'">Đăng ký tài khoản</button>
        </form>
    </div>
   
</body>
</html>