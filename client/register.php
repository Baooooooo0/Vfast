<?php
error_reporting(0);
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="../css/login.css">
    <?php include('home_css.php') ?>
    <style>
        /* Small override to keep the register form centered */
        .login-container { max-width:420px; padding:24px; }
        .message { color: #b71c1c; margin-bottom:12px; }
        .success { color: #1b5e20; }
    </style>
</head>
<body>

    <div class="login-container">
        <i class="fas fa-user-circle"></i>
        <h2>Đăng ký</h2>

        <?php if (!empty($_SESSION['registerMessage'])): ?>
            <div class="message"><?php echo htmlspecialchars($_SESSION['registerMessage']); unset($_SESSION['registerMessage']); ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['registerSuccess'])): ?>
            <div class="message success"><?php echo htmlspecialchars($_SESSION['registerSuccess']); unset($_SESSION['registerSuccess']); ?></div>
        <?php endif; ?>

        <form id="registerForm" action="register_check.php" method="POST" novalidate>
            <input type="text" placeholder="Họ và tên" id="name" name="name" required>
            
            <input type="email" placeholder="Email" id="email" name="email" required>

            <input type="text" placeholder="Số điện thoại" id="phone" name="phone">
            
            <input type="password" placeholder="Mật khẩu" id="password" name="password" required>
            <input type="password" placeholder="Xác nhận mật khẩu" id="password_confirm" name="password_confirm" required>

            <div style="margin:8px 0; text-align:right;"><a href="login.php">Đã có tài khoản? Đăng nhập</a></div>

            <button type="submit" name="submit">Đăng ký</button>
        </form>
    </div>

    <script>
        // Minimal client-side validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            var pw = document.getElementById('password').value;
            var pwc = document.getElementById('password_confirm').value;
            if (pw.length == 0) {
                e.preventDefault();
                alert('Ban phải nhập mật khẩu.');
                return false;
            }
            if (pw !== pwc) {
                e.preventDefault();
                alert('Mật khẩu và xác nhận mật khẩu không khớp.');
                return false;
            }
            return true;
        });
    </script>

</body>
</html>