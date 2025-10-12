<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";
$data = new mysqli($host, $user, $password, $db);

if ($data->connect_error) {
    die("Connection failed: " . $data->connect_error);
}

$user_id = $_SESSION['user_id'];
$feedback_message = '';

// Handle form submission to UPDATE/INSERT address
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_name = $_POST['receiver_name'];
    $receiver_phone = $_POST['receiver_phone'];
    $receiver_address = $_POST['receiver_address'];

    $stmt = $data->prepare("UPDATE users SET receiver_name = ?, receiver_phone = ?, receiver_address = ? WHERE id = ?");
    $stmt->bind_param("sssi", $receiver_name, $receiver_phone, $receiver_address, $user_id);
    
    if ($stmt->execute()) {
        $feedback_message = '<div class="alert alert-success">Cập nhật địa chỉ thành công!</div>';
    } else {
        $feedback_message = '<div class="alert alert-danger">Lỗi! Không thể cập nhật địa chỉ.</div>';
    }
    $stmt->close();
}

// Fetch current user data to display in the form
$stmt = $data->prepare("SELECT name, receiver_name, receiver_phone, receiver_address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$currentUser = $result->fetch_assoc();
$stmt->close();
$data->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý địa chỉ giao hàng</title>
    <?php include('home_css.php'); ?>
    <style>
        .address-container { max-width: 600px; margin: 120px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .address-container h2 { text-align: center; margin-bottom: 20px; }
        .form-group i { position: absolute; left: 15px; top: 12px; color: #888; }
        .form-control { padding-left: 40px; margin-left: 40px; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-danger { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <?php include('header.php'); ?>

    <div class="address-container">
        <h2>Thông tin giao hàng</h2>
        <?php echo $feedback_message; ?>
        <form action="address_crud.php" method="POST">
            <div class="form-group">
                <i class="fa fa-user"></i>
                <input type="text" class="form-control" name="receiver_name" placeholder="Họ và tên người nhận" value="<?php echo htmlspecialchars($currentUser['receiver_name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <i class="fa fa-phone"></i>
                <input type="text" class="form-control" name="receiver_phone" placeholder="Số điện thoại" value="<?php echo htmlspecialchars($currentUser['receiver_phone'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <i class="fa fa-map-marker"></i>
                <input type="text" class="form-control" name="receiver_address" placeholder="Địa chỉ nhận xe" value="<?php echo htmlspecialchars($currentUser['receiver_address'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Lưu thay đổi</button>
            <a href="javascript:history.back()" class="btn btn-secondary" style="width: 100%; margin-top: 10px; text-align: center;">Quay lại</a>
        </form>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>