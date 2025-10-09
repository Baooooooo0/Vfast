<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";

$data = mysqli_connect($host, $user, $password, $db);

if (!$data) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $transaction_id = $_POST['transaction_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $pob = $_POST['pob'];
    $product_name = $_POST['product_name'];
    $color = $_POST['color'];
    $number = $_POST['number'];

    // Bắt đầu một transaction
    mysqli_begin_transaction($data);

    try {
        // Thêm người dùng vào bảng users
        $sql_users = "INSERT INTO users (name, phone, pob) VALUES ('$name', '$phone', '$pob')";
        if (!mysqli_query($data, $sql_users)) {
            throw new Exception("Thêm người dùng thất bại: " . mysqli_error($data));
        }

        // Lấy ID của người dùng vừa thêm
        $user_id = mysqli_insert_id($data);

        // Kiểm tra xem sản phẩm đã tồn tại chưa
        $sql_check_product = "SELECT id FROM product WHERE product_name = '$product_name'";
        $result_check_product = mysqli_query($data, $sql_check_product);

        if (mysqli_num_rows($result_check_product) > 0) {
            // Nếu sản phẩm đã tồn tại, lấy ID của sản phẩm
            $product_row = mysqli_fetch_assoc($result_check_product);
            $product_id = $product_row['id'];
        } else {
            // Nếu sản phẩm chưa tồn tại, thêm sản phẩm mới và lấy ID của sản phẩm
            $sql_product = "INSERT INTO product (product_name) VALUES ('$product_name')";
            if (!mysqli_query($data, $sql_product)) {
                throw new Exception("Thêm sản phẩm thất bại: " . mysqli_error($data));
            }
            $product_id = mysqli_insert_id($data);
        }

        // Thêm giao dịch vào bảng transactions
        $sql_transactions = "INSERT INTO transactions (transaction_id, user_id, product_id, color, number) VALUES ('$transaction_id', '$user_id', '$product_id', '$color', '$number')";
        if (!mysqli_query($data, $sql_transactions)) {
            throw new Exception("Thêm giao dịch thất bại: " . mysqli_error($data));
        }

        // Commit transaction
        mysqli_commit($data);

        echo "<script>alert('Thêm giao dịch thành công.'); window.location.href='../admin/ad_home.php';</script>";
    } catch (Exception $e) {
        // Rollback nếu có lỗi xảy ra
        mysqli_rollback($data);
        echo "<script>alert('Thêm giao dịch thất bại: " . $e->getMessage() . "'); window.location.href='../admin/ad_home.php';</script>";
    }
}

?>
