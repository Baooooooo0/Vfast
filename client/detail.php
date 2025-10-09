<?php 

$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";

$data = mysqli_connect($host, $user, $password, $db);

if (!$data) {
    die("Connection failed: " . mysqli_connect_error());
}

$product_id = $_GET['product_id'];

$sql2 = "SELECT * FROM product WHERE product_id = ?";
$stmt2 = $data->prepare($sql2);
$stmt2->bind_param('s', $product_id);
$stmt2->execute();
$result2 = $stmt2->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>
    <?php include('header.php') ?>
    <div class="content-detail">
        <?php while ($row2 = $result2->fetch_assoc()): ?>
            <div class="product-details">
                <h3><?php echo htmlspecialchars($row2['product_name']); ?></h3>
                <div class="item-content">
                <div class="item-content-img"><img src="<?php echo htmlspecialchars($row2['image']); ?>" alt="<?php echo htmlspecialchars($row2['product_name']); ?>">    </div>
                <div class="items-content">
                    <div>
                    <p><span>Màu:</span> <?php echo htmlspecialchars($row2['color']); ?></p>
                    <p><span>Kích thước:</span> <?php echo htmlspecialchars($row2['dimensions']); ?></p>
                    <p><span>Dung lượng pin:</span> <?php echo htmlspecialchars($row2['battery_capacity']); ?> mAh</p>
                    <p><span>Loại bánh:</span> <?php echo htmlspecialchars($row2['wheel_type']); ?></p>
                    <p><span>Số ghế:</span> <?php echo htmlspecialchars($row2['seat_count']); ?></p>
                    <p><span>Số lượng túi khí:</span> <?php echo htmlspecialchars($row2['airbags']); ?></p>
                    <p class="product-price"><span>Giá: </span><?php echo number_format($row2['product_price'], 0, ',', '.'); ?> VND</p>
                    </div>
                </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <?php include('footer.php') ?>

</body>
</html>

