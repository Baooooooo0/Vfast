<?php

// Yêu cầu phải là POST và nút "update_product" phải được nhấn
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST['update_product'])) {
    header("Location: ad_home.php?page=products");
    exit();
}

// 1. KẾT NỐI DATABASE
$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";
$data = mysqli_connect($host, $user, $password, $db);

if (!$data) {
    die("Connection failed: " . mysqli_connect_error());
}

// 2. LẤY DỮ LIỆU TỪ FORM
$product_id = $_POST['product_id'];
$new_price = $_POST['new_price'];
$new_stock = $_POST['new_stock'];

// 3. KIỂM TRA DỮ LIỆU
// Đảm bảo giá và tồn kho là số và không âm
if (isset($product_id) && 
    isset($new_price) && is_numeric($new_price) && $new_price >= 0 &&
    isset($new_stock) && is_numeric($new_stock) && $new_stock >= 0) {

    // 4. CẬP NHẬT DATABASE
    $sql = "UPDATE product SET product_price = ?, product_number = ? WHERE product_id = ?";
    $stmt = mysqli_prepare($data, $sql);
    
    if ($stmt) {
        // "dds" = double, double, string
        mysqli_stmt_bind_param($stmt, "dds", $new_price, $new_stock, $product_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo '<script>location.replace("ad_home.php");</script>';
        exit(); // Dừng script ngay sau khi chuyển hướng

    } else {
        // Lỗi SQL
        header("Location: ad_home.php?page=products&update=sql_error");
    }
} else {
    // Dữ liệu không hợp lệ (ví dụ: nhập chữ, số âm)
    header("Location: ad_home.php?page=products&update=invalid_data");
}

mysqli_close($data);
exit();
?>