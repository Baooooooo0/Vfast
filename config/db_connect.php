<?php
// ================================
// 🔗 Kết nối Database (XAMPP)
// ================================

$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'carshop';

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conn) {
    die('❌ Không thể kết nối tới MySQL: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
?>
