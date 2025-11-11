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
        .login-container {
            max-width: 420px;
            padding: 24px;
            max-height: none !important;
            /* Remove height restriction */
            height: auto !important;
            /* Let it expand naturally */
            overflow: visible !important;
            /* Ensure content is visible */
        }

        .message {
            color: #b71c1c;
            margin-bottom: 12px;
        }

        .success {
            color: #1b5e20;
        }

        /* Style for select dropdown */
        select {
            width: calc(100% - 22px) !important;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
            background-color: white;
            color: #333;
            box-sizing: border-box;
        }

        select option {
            padding: 8px;
        }

        /* Style for date input */
        input[type="date"] {
            width: calc(100% - 22px) !important;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
            box-sizing: border-box;
        }

        /* Ensure all inputs have consistent styling */
        .login-container input {
            box-sizing: border-box;
        }

        /* Adjust body to handle taller forms */
        body {
            min-height: 100vh !important;
            height: auto !important;
            padding: 20px 0 !important;
        }

        /* For smaller screens */
        @media (max-height: 700px) {
            body {
                align-items: flex-start !important;
                padding-top: 50px !important;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <i class="fas fa-user-circle"></i>
        <h2>Đăng ký</h2>

        <?php if (!empty($_SESSION['registerMessage'])): ?>
            <div class="message"><?php echo htmlspecialchars($_SESSION['registerMessage']);
                                    unset($_SESSION['registerMessage']); ?></div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['registerSuccess'])): ?>
            <div class="message success"><?php echo htmlspecialchars($_SESSION['registerSuccess']);
                                            unset($_SESSION['registerSuccess']); ?></div>
        <?php endif; ?>

        <form id="registerForm" action="register_check.php" method="POST" novalidate>
            <input type="text" placeholder="Họ và tên" id="name" name="name" required>

            <input type="email" placeholder="Email" id="email" name="email" required>

            <input type="tel" placeholder="Số điện thoại" id="phone" name="phone" pattern="[0-9]{10,11}" title="Nhập 10-11 số">

            <input type="date" placeholder="Ngày sinh" id="birthday" name="birthday">

            <select id="pob" name="pob" title="Chọn nơi sinh">
                <option value="">Chọn nơi sinh</option>
                <option value="Hà Nội">Hà Nội</option>
                <option value="Hồ Chí Minh">Hồ Chí Minh</option>
                <option value="Hải Phòng">Hải Phòng</option>
                <option value="Đà Nẵng">Đà Nẵng</option>
                <option value="Cần Thơ">Cần Thơ</option>
                <option value="An Giang">An Giang</option>
                <option value="Bà Rịa - Vũng Tàu">Bà Rịa - Vũng Tàu</option>
                <option value="Bắc Giang">Bắc Giang</option>
                <option value="Bắc Kạn">Bắc Kạn</option>
                <option value="Bạc Liêu">Bạc Liêu</option>
                <option value="Bắc Ninh">Bắc Ninh</option>
                <option value="Bến Tre">Bến Tre</option>
                <option value="Bình Định">Bình Định</option>
                <option value="Bình Dương">Bình Dương</option>
                <option value="Bình Phước">Bình Phước</option>
                <option value="Bình Thuận">Bình Thuận</option>
                <option value="Cà Mau">Cà Mau</option>
                <option value="Cao Bằng">Cao Bằng</option>
                <option value="Đắk Lắk">Đắk Lắk</option>
                <option value="Đắk Nông">Đắk Nông</option>
                <option value="Điện Biên">Điện Biên</option>
                <option value="Đồng Nai">Đồng Nai</option>
                <option value="Đồng Tháp">Đồng Tháp</option>
                <option value="Gia Lai">Gia Lai</option>
                <option value="Hà Giang">Hà Giang</option>
                <option value="Hà Nam">Hà Nam</option>
                <option value="Hà Tĩnh">Hà Tĩnh</option>
                <option value="Hải Dương">Hải Dương</option>
                <option value="Hậu Giang">Hậu Giang</option>
                <option value="Hòa Bình">Hòa Bình</option>
                <option value="Hưng Yên">Hưng Yên</option>
                <option value="Khánh Hòa">Khánh Hòa</option>
                <option value="Kiên Giang">Kiên Giang</option>
                <option value="Kon Tum">Kon Tum</option>
                <option value="Lai Châu">Lai Châu</option>
                <option value="Lâm Đồng">Lâm Đồng</option>
                <option value="Lạng Sơn">Lạng Sơn</option>
                <option value="Lào Cai">Lào Cai</option>
                <option value="Long An">Long An</option>
                <option value="Nam Định">Nam Định</option>
                <option value="Nghệ An">Nghệ An</option>
                <option value="Ninh Bình">Ninh Bình</option>
                <option value="Ninh Thuận">Ninh Thuận</option>
                <option value="Phú Thọ">Phú Thọ</option>
                <option value="Phú Yên">Phú Yên</option>
                <option value="Quảng Bình">Quảng Bình</option>
                <option value="Quảng Nam">Quảng Nam</option>
                <option value="Quảng Ngãi">Quảng Ngãi</option>
                <option value="Quảng Ninh">Quảng Ninh</option>
                <option value="Quảng Trị">Quảng Trị</option>
                <option value="Sóc Trăng">Sóc Trăng</option>
                <option value="Sơn La">Sơn La</option>
                <option value="Tây Ninh">Tây Ninh</option>
                <option value="Thái Bình">Thái Bình</option>
                <option value="Thái Nguyên">Thái Nguyên</option>
                <option value="Thanh Hóa">Thanh Hóa</option>
                <option value="Thừa Thiên Huế">Thừa Thiên Huế</option>
                <option value="Tiền Giang">Tiền Giang</option>
                <option value="Trà Vinh">Trà Vinh</option>
                <option value="Tuyên Quang">Tuyên Quang</option>
                <option value="Vĩnh Long">Vĩnh Long</option>
                <option value="Vĩnh Phúc">Vĩnh Phúc</option>
                <option value="Yên Bái">Yên Bái</option>
            </select>

            <input type="password" placeholder="Mật khẩu" id="password" name="password" required>
            <input type="password" placeholder="Xác nhận mật khẩu" id="password_confirm" name="password_confirm" required>

            <div style="margin:8px 0; text-align:right;"><a href="login.php">Đã có tài khoản? Đăng nhập</a></div>

            <button type="submit" name="submit">Đăng ký</button>
        </form>
    </div>

    <script>
        // Minimal client-side validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            var name = document.getElementById('name').value.trim();
            var email = document.getElementById('email').value.trim();
            var phone = document.getElementById('phone').value.trim();
            var birthday = document.getElementById('birthday').value;
            var pw = document.getElementById('password').value;
            var pwc = document.getElementById('password_confirm').value;

            // Kiểm tra tên
            if (name.length == 0) {
                e.preventDefault();
                alert('Bạn phải nhập họ và tên.');
                return false;
            }

            // Kiểm tra email
            if (email.length == 0) {
                e.preventDefault();
                alert('Bạn phải nhập email.');
                return false;
            }

            // Kiểm tra số điện thoại nếu có nhập
            if (phone.length > 0 && !/^[0-9]{10,11}$/.test(phone)) {
                e.preventDefault();
                alert('Số điện thoại phải có 10-11 chữ số.');
                return false;
            }

            // Kiểm tra mật khẩu
            if (pw.length == 0) {
                e.preventDefault();
                alert('Bạn phải nhập mật khẩu.');
                return false;
            }

            if (pw.length < 3) {
                e.preventDefault();
                alert('Mật khẩu phải có ít nhất 3 ký tự.');
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