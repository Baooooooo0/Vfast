<?php
// admin/update_account.php

// 1. KIỂM TRA YÊU CẦU
// Yêu cầu phải là POST và nút "update_user_role" phải được nhấn
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST['update_user_role'])) {
    header("Location: ad_home.php?page=users");
    exit();
}

// 2. KẾT NỐI DATABASE
$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";
$data = mysqli_connect($host, $user, $password, $db);

if (!$data) {
    die("Connection failed: " . mysqli_connect_error());
}

// 3. LẤY DỮ LIỆU TỪ FORM
$user_id = $_POST['user_id'];
$new_usertype = $_POST['new_usertype'];

// 4. KIỂM TRA DỮ LIỆU HỢP LỆ
$allowed_types = ['user', 'admin'];
if (isset($user_id) && in_array($new_usertype, $allowed_types)) {

    // 5. CẬP NHẬT DATABASE
    $sql = "UPDATE users SET usertype = ? WHERE id = ?";
    $stmt = mysqli_prepare($data, $sql);
    
    if ($stmt) {
        // "si" = string (usertype), integer (id)
        mysqli_stmt_bind_param($stmt, "si", $new_usertype, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // 6. CHUYỂN HƯỚNG VỀ BÁO THÀNH CÔNG
        echo '<script>location.replace("ad_home.php");</script>';

    } else {
        // Lỗi SQL
        header("Location: ad_home.php?page=users&update=sql_error");
    }
} else {
    // Dữ liệu không hợp lệ (ví dụ: ai đó cố tình sửa giá trị POST)
    header("Location: ad_home.php?page=users&update=invalid_data");
}

mysqli_close($data);
exit();
?>