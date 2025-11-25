<?php
// ================================
// 🔗 Kết nối Database (Railway + Local)
// ================================

// Railway → lấy từ biến môi trường
// Local (XAMPP) → dùng giá trị mặc định
$DB_HOST = getenv('MYSQLHOST') ?: '127.0.0.1';
$DB_USER = getenv('MYSQLUSER') ?: 'root';
$DB_PASS = getenv('MYSQLPASSWORD') ?: '';
$DB_NAME = getenv('MYSQLDATABASE') ?: 'carshop';
$DB_PORT = getenv('MYSQLPORT') ?: 3306;

// Kết nối MySQL
$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

// Kiểm tra lỗi
if (!$conn) {
    die('❌ Không thể kết nối tới MySQL: ' . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, 'utf8mb4');
?>
