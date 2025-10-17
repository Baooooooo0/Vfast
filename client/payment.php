<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";
$data = new mysqli($host, $user, $password, $db);
if ($data->connect_error) {
    die("Connection failed: " . $data->connect_error);
}

// Get product details from POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: oto.php');
    exit;
}

$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];
$color = $_POST['color'];
$image = $_POST['image'];
$quantity = $_POST['quantity'];
$deposit_amount = 15000000;
$total_price = $product_price * $quantity;

// --- Fetch User's Delivery Address ---
$user_id = $_SESSION['user_id'];
$user_address = null;
$sql_user = "SELECT receiver_name, receiver_phone, receiver_address FROM users WHERE id = ?";
$stmt_user = $data->prepare($sql_user);
$stmt_user->bind_param('i', $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
if ($result_user->num_rows > 0) {
    $user_address = $result_user->fetch_assoc();
    // Check if the address fields are filled
    if (empty($user_address['receiver_name']) || empty($user_address['receiver_phone']) || empty($user_address['receiver_address'])) {
        $user_address = null;
    }
}
$stmt_user->close();
$data->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <?php include('home_css.php'); ?>
    <style>
    body {
        background-color: #f8f9fa;
    }

    .payment-container {
        max-width: 800px;
        margin: 120px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .payment-container h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #007bff;
    }

    .product-summary {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e9ecef;
    }

    .product-summary img {
        width: 150px;
        height: auto;
        border-radius: 8px;
        margin-right: 20px;
    }

    .product-info h3 {
        margin: 0 0 10px;
        font-size: 22px;
    }

    .product-info p {
        margin: 5px 0;
        color: #6c757d;
    }

    .product-info h4 {
        margin-top: 15px;
        font-size: 24px;
        color: #e63946;
        font-weight: bold;
    }

    .delivery-info {
        margin-bottom: 30px;
    }

    .delivery-info h5 {
        margin-bottom: 15px;
    }

    .address-display p {
        margin: 5px 0;
    }

    .payment-options {
        margin-top: 20px;
    }

    .payment-option {
        padding: 20px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .payment-option h3 {
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 18px;
    }

    .payment-methods button {
        width: 48%;
        padding: 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .btn-momo {
        background-color: #a50064;
        color: white;
    }

    .btn-momo:hover {
        background-color: #8c0054;
    }

    .btn-bank {
        background-color: #007bff;
        color: white;
        float: right;
    }

    .btn-bank:hover {
        background-color: #0056b3;
    }
    </style>
    <link rel="stylesheet" href="../css/payment.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="payment-container">
        <h2>Xác nhận đơn hàng và thanh toán</h2>

        <div class="product-summary">
            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product_name); ?>">
            <div class="product-info">
                <h3><?php echo htmlspecialchars($product_name); ?></h3>
                <p>Màu: <?php echo htmlspecialchars($color); ?></p>
                <p>Số lượng: <?php echo htmlspecialchars($quantity); ?></p>
                <h4>Tổng tiền: <?php echo number_format($total_price, 0, ',', '.'); ?> VND</h4>
            </div>
        </div>
        
        <div class="delivery-info">
            <h5>Thông tin giao hàng</h5>
            <?php if ($user_address): ?>
                <div class="address-display">
                    <p><strong>Tên người nhận:</strong> <?php echo htmlspecialchars($user_address['receiver_name']); ?></p>
                    <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($user_address['receiver_phone']); ?></p>
                    <p><strong>Địa chỉ giao xe:</strong> <?php echo htmlspecialchars($user_address['receiver_address']); ?></p>
                    <a href="javascript:history.back()" class="btn btn-secondary btn-sm" style="margin-top:10px;">Quay lại</a>                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    Bạn chưa có thông tin giao hàng. Vui lòng cập nhật để tiếp tục.
                </div>
                <a href="address_crud.php?product_id=<?php echo htmlspecialchars($product_id); ?>" class="btn btn-primary">Thêm địa chỉ giao hàng</a>
            <?php endif; ?>
        </div>

        <div class="payment-options">
            <div class="payment-option">
                <h3>1. Đặt cọc (<?php echo number_format($deposit_amount, 0, ',', '.'); ?> VND)</h3>
                <div class="payment-methods">
                     <form method="POST" action="MoMo.php">
                        <input type="hidden" name="amount" value="<?php echo (int)$deposit_amount; ?>">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                        <input type="hidden" name="quantity" value="<?php echo (int)$quantity; ?>">
                        <input type="hidden" name="payment_type" value="deposit">
                        
                        <?php if ($user_address): ?>
                            <input type="hidden" name="receiver_name" value="<?php echo htmlspecialchars($user_address['receiver_name']); ?>">
                            <input type="hidden" name="receiver_phone" value="<?php echo htmlspecialchars($user_address['receiver_phone']); ?>">
                            <input type="hidden" name="receiver_address" value="<?php echo htmlspecialchars($user_address['receiver_address']); ?>">
                        <?php endif; ?>
                        
                        <button type="submit" class="btn-momo" <?php echo $user_address ? '' : 'disabled'; ?>>Thanh toán Momo</button>
                    </form>

                    <form action="bank.php" method="POST">
                        <input type="hidden" name="payment_method" value="bank">
                        <input type="hidden" name="payment_type" value="deposit">
                        <input type="hidden" name="amount" value="<?php echo (int)$deposit_amount; ?>">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>">
                        <input type="hidden" name="product_price" value="<?php echo (int)$product_price; ?>">
                        <input type="hidden" name="quantity" value="<?php echo (int)$quantity; ?>">
                        <input type="hidden" name="color" value="<?php echo htmlspecialchars($color); ?>">
                        <input type="hidden" name="image" value="<?php echo htmlspecialchars($image); ?>">
                        <?php if ($user_address): ?>
                            <input type="hidden" name="receiver_name" value="<?php echo htmlspecialchars($user_address['receiver_name']); ?>">
                            <input type="hidden" name="receiver_phone" value="<?php echo htmlspecialchars($user_address['receiver_phone']); ?>">
                            <input type="hidden" name="receiver_address" value="<?php echo htmlspecialchars($user_address['receiver_address']); ?>">
                        <?php endif; ?>
                        <button class="btn-bank" type="submit" <?php echo $user_address ? '' : 'disabled'; ?>>Chuyển khoản ngân hàng</button>
                    </form>
                </div>
            </div>

            <div class="payment-option">
                <h3>2. Thanh toán toàn bộ (<?php echo number_format($total_price, 0, ',', '.'); ?> VND)</h3>
                <div class="payment-methods">
                     <form method="POST" action="MoMo.php">
                        <input type="hidden" name="amount" value="<?php echo (int)$total_price; ?>">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                        <input type="hidden" name="quantity" value="<?php echo (int)$quantity; ?>">
                        <input type="hidden" name="payment_type" value="full">

                         <?php if ($user_address): ?>
                            <input type="hidden" name="receiver_name" value="<?php echo htmlspecialchars($user_address['receiver_name']); ?>">
                            <input type="hidden" name="receiver_phone" value="<?php echo htmlspecialchars($user_address['receiver_phone']); ?>">
                            <input type="hidden" name="receiver_address" value="<?php echo htmlspecialchars($user_address['receiver_address']); ?>">
                        <?php endif; ?>

                        <button type="submit" class="btn-momo" <?php echo $user_address ? '' : 'disabled'; ?>>Thanh toán Momo</button>
                    </form>

                    <form action="bank.php" method="POST" style="display:inline-block; width:48%;">
                    <input type="hidden" name="payment_method" value="bank">
                    <input type="hidden" name="payment_type" value="full">
                    <input type="hidden" name="amount" value="<?php echo (int)$total_price; ?>">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                    <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>">
                    <input type="hidden" name="product_price" value="<?php echo (int)$product_price; ?>">
                    <input type="hidden" name="quantity" value="<?php echo (int)$quantity; ?>">
                    <input type="hidden" name="color" value="<?php echo htmlspecialchars($color); ?>">
                    <input type="hidden" name="image" value="<?php echo htmlspecialchars($image); ?>">
                    <?php if ($user_address): ?>
                        <input type="hidden" name="receiver_name" value="<?php echo htmlspecialchars($user_address['receiver_name']); ?>">
                        <input type="hidden" name="receiver_phone" value="<?php echo htmlspecialchars($user_address['receiver_phone']); ?>">
                        <input type="hidden" name="receiver_address" value="<?php echo htmlspecialchars($user_address['receiver_address']); ?>">
                    <?php endif; ?>
                    <button class="btn-bank" type="submit" <?php echo $user_address ? '' : 'disabled'; ?>>Chuyển khoản ngân hàng</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>