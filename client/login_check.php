<?php
error_reporting(0);
session_start();

// Use the project's DB connection
require_once __DIR__ . '/../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // So sánh mật khẩu trực tiếp (plaintext)
    $sql = "SELECT id, email, password, usertype FROM users WHERE email = ? AND password = ? LIMIT 1";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, 'ss', $email, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = $result ? mysqli_fetch_assoc($result) : null;

        if ($row) {
            // Successful login
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['usertype'] = $row['usertype'];

            mysqli_stmt_close($stmt);

            if ($row['usertype'] === 'admin') {
                header('Location: ../admin/ad_home.php');
                exit();
            } else {
                header('Location: home.php');
                exit();
            }
        } else {
            // Login failed
            mysqli_stmt_close($stmt);
            $_SESSION['loginMessage'] = 'Email và Password không tồn tại!';
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['loginMessage'] = 'Lỗi hệ thống. Vui lòng thử lại.';
        header('Location: login.php');
        exit();
    }
}
