    <?php 
    session_start();
    if(!isset($_SESSION['email'])){
        header("Location: login.php");
        exit();
    }
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "carshop";

    $data = mysqli_connect($host, $user, $password, $db);

    if (!$data) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $product_id = $_GET['product_id'];

    //Fetch product data only ONCE and store it in an array
    $sql = "SELECT * FROM product WHERE product_id = ?";
    $stmt = $data->prepare($sql);
    $stmt->bind_param('s', $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc(); // $product is now an associative array
    echo $product_id;
    // Redirect or show error if product not found
    if (!$product) {
        die("Sản phẩm không tồn tại.");
    }

    // --- Fetch User's Delivery Address ---
    $user_address = null;
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $sql_user = "SELECT receiver_name, receiver_phone, receiver_address FROM users WHERE id = ?";
        $stmt_user = $data->prepare($sql_user);
        $stmt_user->bind_param('i', $user_id);
        $stmt_user->execute();
        $user_address = $stmt_user->get_result()->fetch_assoc();
        // Check if the address fields are filled
        if (empty($user_address['receiver_name']) || empty($user_address['receiver_phone']) || empty($user_address['receiver_address'])) {
            $user_address = null;
        }
        
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chi tiết sản phẩm</title>
        <?php include('home_css.php'); ?>
        <link rel="stylesheet" href="../css/style.css">
        
    </head>
    <body>
        <?php include('header.php') ?>
        <div class="content-detail">
            <div class="product-details">
                <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                <div class="item-content">
                    <div class="item-content-img">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    </div>
                    <div class="items-content">
                        <div>
                            <p><span>Màu:</span> <?php echo htmlspecialchars($product['color']); ?></p>
                            <p><span>Tồn kho:</span> <?php echo htmlspecialchars($product['product_number']); ?></p>
                            <p><span>Kích thước:</span> <?php echo htmlspecialchars($product['dimensions']); ?></p>
                            <p><span>Dung lượng pin:</span> <?php echo htmlspecialchars($product['battery_capacity']); ?> kWh</p>
                            <p><span>Loại bánh:</span> <?php echo htmlspecialchars($product['wheel_type']); ?></p>
                            <p><span>Số ghế:</span> <?php echo htmlspecialchars($product['seat_count']); ?></p>
                            <p><span>Số lượng túi khí:</span> <?php echo htmlspecialchars($product['airbags']); ?></p>
                            <p class="product-price"><span>Giá: </span><?php echo number_format($product['product_price'], 0, ',', '.'); ?> VND</p>
                            <!-- add purcharse product and use logic to handle in stock -->
                            <?php if ($product['product_number'] > 0): ?>
                                <button class="details" data-toggle="modal" data-target="#buyNowModal">Mua ngay</button>
                            <?php else: ?>
                                <button class="details" disabled style="background-color: #ccc; cursor: not-allowed;">Hết hàng</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="buyNowModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Xác nhận đơn hàng</h4>
                        <button type="button" class="close" data-dismiss="modal">X</button>
                    </div>
                    <div class="modal-body" style="padding: 10px 10px;">
                        <form action="payment.php" method="POST" id="buyNowForm">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>">
                            <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['product_price']); ?>">
                            <input type="hidden" name="color" value="<?php echo htmlspecialchars($product['color']); ?>">
                            <input type="hidden" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
                            
                            <h5 style="margin-top: 10px; margin-bottom: 15px;">Thông tin giao hàng</h5>
                            <?php if ($user_address): ?>
                                <div class="address-display" style="margin-bottom: 15px;">
                                    <p><strong>Tên:</strong> <?php echo htmlspecialchars($user_address['receiver_name']); ?></p>
                                    <p><strong>SĐT:</strong> <?php echo htmlspecialchars($user_address['receiver_phone']); ?></p>
                                    <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($user_address['receiver_address']); ?></p>
                                    <a href="../client/address_crud.php?product_id=<?php echo htmlspecialchars($product['product_id']) ?>" class="btn btn-primary" style="margin-bottom: 15px; display: block; text-align: center;">chỉnh sửa</a>

                                </div>
                                <input type="hidden" name="receiver_name" value="<?php echo htmlspecialchars($user_address['receiver_name']); ?>">
                                <input type="hidden" name="receiver_phone" value="<?php echo htmlspecialchars($user_address['receiver_phone']); ?>">
                                <input type="hidden" name="receiver_address" value="<?php echo htmlspecialchars($user_address['receiver_address']); ?>">
                            <?php else: ?>
                                <div class="alert alert-warning" style="margin-bottom: 15px;">
                                    Vui lòng thêm thông tin giao hàng để tiếp tục.
                                </div>
                                
                                <a href="../client/address_crud.php?product_id=<?php echo htmlspecialchars($product['product_id']) ?>" class="btn btn-primary" style="margin-bottom: 15px; display: block; text-align: center;">Thêm địa chỉ giao hàng</a>
                            <?php endif; ?>
                            
                            <hr style="margin-top: 20px; margin-bottom: 20px;">
                            
                            <h5 style="margin-top: 10px; margin-bottom: 15px;">Số lượng đặt mua</h5>
                            <div style="margin-bottom: 15px;">
                                <input type="number" class="form-control" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($product['product_number']); ?>" required style="padding-left: 10px; width: 100%; height: 40px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;">
                            </div>
                            
                            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 15px;" <?php if (!$user_address) echo 'disabled'; ?>>
                                Xác nhận và Thanh toán
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include('footer.php') ?>

    </body>
    </html>