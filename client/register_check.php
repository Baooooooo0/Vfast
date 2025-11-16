<?php
// 1. Luôn bắt đầu session ở đầu file
session_start();

// Bật hiển thị lỗi (dùng khi debug, nên tắt khi chạy thật)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 2. Yêu cầu file kết nối CSDL
require_once __DIR__ . '/../config/db_connect.php';

// 3. Chỉ chạy code nếu form được gửi bằng POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // 4. Lấy dữ liệu từ form và làm sạch (dùng trim)
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $birthday = $_POST['birthday'] ?? null;
    $pob = trim($_POST['pob'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // === 5. KIỂM TRA DỮ LIỆU (VALIDATION) ===

    // Kiểm tra trường rỗng
    if (empty($name) || empty($email) || empty($password) || empty($password_confirm)) {
        $_SESSION['registerMessage'] = 'Vui lòng điền đầy đủ các trường bắt buộc.';
        header('Location: register.php');
        exit();
    }

    // Kiểm tra định dạng email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['registerMessage'] = 'Email không hợp lệ.';
        header('Location: register.php');
        exit();
    }

    // Kiểm tra mật khẩu khớp nhau
    if ($password !== $password_confirm) {
        $_SESSION['registerMessage'] = 'Mật khẩu và xác nhận mật khẩu không khớp.';
        header('Location: register.php');
        exit();
    }

    // Kiểm tra số điện thoại (nếu có nhập)
    if (!empty($phone) && !preg_match('/^[0-9]{10,11}$/', $phone)) {
        $_SESSION['registerMessage'] = 'Số điện thoại phải có 10-11 chữ số.';
        header('Location: register.php');
        exit();
    }

    // Kiểm tra ngày sinh (nếu có nhập)
    if (!empty($birthday)) {
        $dateCheck = DateTime::createFromFormat('Y-m-d', $birthday);
        if (!$dateCheck || $dateCheck->format('Y-m-d') !== $birthday) {
            $_SESSION['registerMessage'] = 'Ngày sinh không hợp lệ.';
            header('Location: register.php');
            exit();
        }

        // Kiểm tra tuổi hợp lý (từ 16 đến 100 tuổi)
        $today = new DateTime();
        $age = $today->diff($dateCheck)->y;
        if ($age < 16 || $age > 100) {
            $_SESSION['registerMessage'] = 'Tuổi phải từ 16 đến 100.';
            header('Location: register.php');
            exit();
        }
    }

    // === 6. KIỂM TRA EMAIL TỒN TẠI (Đã thêm try...catch) ===
    try {
        $check_isset = "SELECT id FROM users WHERE email = ? LIMIT 1";
        $stmt_check = mysqli_prepare($conn, $check_isset);
        mysqli_stmt_bind_param($stmt_check, 's', $email);
        mysqli_stmt_execute($stmt_check);

        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $_SESSION['registerMessage'] = 'Email đã được sử dụng. Vui lòng chọn email khác.';
            mysqli_stmt_close($stmt_check); // Đóng stmt
            mysqli_close($conn); // Đóng kết nối
            header('Location: register.php');
            exit();
        }
        mysqli_stmt_close($stmt_check); // Đóng stmt sau khi dùng xong

    } catch (mysqli_sql_exception $e) {
        $_SESSION['registerMessage'] = 'Lỗi hệ thống khi kiểm tra email.';
        error_log('Register email check error: ' . $e->getMessage());
        mysqli_close($conn);
        header('Location: register.php');
        exit();
    }


    // === 7. KHÔNG MÃ HÓA MẬT KHẨU (sử dụng plaintext) ===
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Bỏ hash

    // === 8. CHÈN VÀO CSDL với mật khẩu plaintext ===
    $insertSql = "INSERT INTO users (name, email, phone, birthday, pob, password, usertype) VALUES (?, ?, ?, ?, ?, ?, 'user')";

    try {
        // Chuẩn bị câu lệnh
        $stmt_insert = mysqli_prepare($conn, $insertSql);

        // Gán 6 biến vào 6 dấu ? - sử dụng mật khẩu gốc
        mysqli_stmt_bind_param($stmt_insert, 'ssssss', $name, $email, $phone, $birthday, $pob, $password);

        // Thực thi
        if (mysqli_stmt_execute($stmt_insert)) {
            // Đăng ký THÀNH CÔNG
            $_SESSION['registerSuccess'] = 'Đăng ký thành công. Vui lòng đăng nhập.';

            // Dọn dẹp và chuyển hướng SANG TRANG LOGIN
            mysqli_stmt_close($stmt_insert);
            mysqli_close($conn);
            header('Location: login.php');
            exit(); // Rất quan trọng: Dừng script ở đây
        } else {
            // Lỗi thực thi nhưng không ném exception (hiếm)
            $_SESSION['registerMessage'] = 'Lỗi không xác định khi thực thi câu lệnh.';
            error_log('Register execute error (no exception): ' . mysqli_error($conn));
        }
    } catch (mysqli_sql_exception $e) {
        // Lỗi THẤT BẠI (vd: Cột không tồn tại, CSDL sập, v.v.)

        // === DÙNG ĐỂ DEBUG (như bạn yêu cầu) ===
        echo "LỖI SQL KHI INSERT: <br>";
        echo $e->getMessage();
        exit(); // Dừng lại để xem lỗi

        /* === CODE KHI CHẠY THẬT (bạn sẽ dùng sau) ===
        $_SESSION['registerMessage'] = 'Lỗi hệ thống. Vui lòng thử lại sau.';
        error_log('Register insert exception: ' . $e->getMessage());
        */
    }

    // Nếu code chạy tới đây, nghĩa là đã có lỗi ở khối TRY (nhưng catch không exit)
    // Chuyển hướng TRỞ LẠI TRANG REGISTER
    mysqli_close($conn); // Đảm bảo đóng kết nối
    header('Location: register.php');
    exit();
} else {
    // Nếu ai đó cố truy cập file này trực tiếp, đá về trang chủ
    header('Location: ../index.php');
    exit();
}
