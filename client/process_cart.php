<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";

$data = new mysqli($host, $user, $password, $db);

// Kiểm tra kết nối
if ($data->connect_error) {
    die("Kết nối thất bại: " . $data->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nhận dữ liệu từ form
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $place = $_POST['place'];
    $showroom = $_POST['showroom'];
    $product_name = $_POST['product_name'];
    $color = $_POST['color'];
    $number = $_POST['transaction_number'];
    $payment_method = $_POST['payment_method'];
    $deposit = 15000000;
    $transaction_date = new DateTime();



    // echo $product_name;
    // Bắt đầu giao dịch
    mysqli_begin_transaction($data);


    
    try {
        // Kiểm tra và chèn người dùng
        $stmt = $data->prepare("SELECT id FROM users WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $users_row = $result->fetch_assoc();
            $users_id = $users_row['id'];
        } else {
            $stmt = $data->prepare("INSERT INTO users (name, phone, email, pob) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $phone, $email, $place);
            if (!$stmt->execute()) {
                throw new Exception("Thêm người dùng thất bại: " . $data->error);
            }
            $users_id = $data->insert_id;
        }

        // Kiểm tra và chèn vị trí
        $stmt = $data->prepare("SELECT location_id FROM locations WHERE location_name = ? AND location_address = ?");
        $stmt->bind_param("ss", $place, $showroom);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $locations_row = $result->fetch_assoc();
            $locations_id = $locations_row['location_id'];
        } else {
            $stmt = $data->prepare("INSERT INTO locations (location_name, location_address) VALUES (?, ?)");
            $stmt->bind_param("ss", $place, $showroom);
            if (!$stmt->execute()) {
                throw new Exception("Thêm vị trí thất bại: " . $data->error);
            }
            $locations_id = $data->insert_id;
        }

        // Kiểm tra  sản phẩm
        $stmt = $data->prepare("SELECT product_id FROM product WHERE product_name = ? and color = ?");
        $stmt->bind_param("ss", $product_name,$color);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $products_row = $result->fetch_assoc();
            $products_id = $products_row['product_id'];
        }
        echo $products_id;

        // Chèn giao dịch
        $formatted_date = $transaction_date->format('Y-m-d H:i:s');
        $stmt = $data->prepare("INSERT INTO transactions (product_id, customer_id, transaction_date, deposit, transaction_number, payment_method, transaction_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $transaction_status = "Đã cọc";
        $stmt->bind_param("sisssss", $products_id, $users_id, $formatted_date, $deposit, $number, $payment_method, $transaction_status);
        if (!$stmt->execute()) {
            throw new Exception("Thêm giao dịch thất bại: " . $data->error);
        }
        
        // Cam kết giao dịch
        mysqli_commit($data);
        header("Location: ../client/oto.php");
        exit;
    } catch (Exception $e) {
        // Rollback giao dịch nếu có lỗi
        mysqli_rollback($data);
        echo "Lỗi: " . $e->getMessage();
    }
}

?>
