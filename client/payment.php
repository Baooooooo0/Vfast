<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redirect if not a POST request
    header('Location: oto.php');
    exit;
}

$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];
$color = $_POST['color'];
$image = $_POST['image'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$quantity = $_POST['quantity'];
$deposit_amount = 15000000;
$total_price = $product_price * $quantity;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <?php include('home_css.php'); ?>
    <link rel="stylesheet" href="../css/payment.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="payment-container">
        <h2>Xác nhận thanh toán</h2>

        <div class="product-summary">
            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product_name); ?>">
            <div class="product-info">
                <h3><?php echo htmlspecialchars($product_name); ?></h3>
                <p>Màu: <?php echo htmlspecialchars($color); ?></p>
                <p>Số lượng: <?php echo htmlspecialchars($quantity); ?></p>
                <p>Giá: <?php echo number_format($product_price, 0, ',', '.'); ?> VND</p>
                <h4>Tổng tiền: <?php echo number_format($total_price, 0, ',', '.'); ?> VND</h4>
            </div>
        </div>

        <div class="payment-options">
            <div class="payment-option">
                <h3>1. Đặt cọc (15,000,000 VND)</h3>
                <div class="payment-methods">
                    <button class="btn-momo">Thanh toán bằng Momo</button>
                    <button class="btn-bank">Chuyển khoản ngân hàng</button>
                </div>
            </div>

            <div class="payment-option">
                <h3>2. Thanh toán toàn bộ</h3>
                <div class="payment-methods">
                    <button class="btn-momo">Thanh toán bằng Momo</button>
                    <button class="btn-bank">Chuyển khoản ngân hàng</button>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>