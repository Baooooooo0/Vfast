<?php

session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";

$data = mysqli_connect($host, $user, $password, $db);

if (!$data) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_SESSION['name'])) {
    $name = $_SESSION['name'];

 
    $stmt = $data->prepare("SELECT id FROM users WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row) {
        $id = $row['id'];
    } else {
        die("Không tìm thấy users.");
    }
    $stmt = $data->prepare("SELECT product.product_name, product.color, product.product_price, transactions.deposit, transactions.transaction_date,transactions.transaction_number
        FROM transactions
        INNER JOIN product ON product.product_id = transactions.product_id
        INNER JOIN users ON users.id = transactions.customer_id 
        WHERE transactions.customer_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result_transactions = $stmt->get_result();
} else {
    die("Lỗi session.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn xe đã cọc</title>
    <?php include('home_css.php'); ?>
</head>
<body>
    <?php include('header.php'); ?>
    <div class="container-car">
        <div class="container_cart">
            <div class="title_cart">
                <h4 class="title_item_cart">Đơn xe đã cọc</h4>
                <hr>
            </div>
            <div class="brand">
                <i class="fa fa-car" aria-hidden="true"></i> 
                <span>Vinfast</span>
                <div class="group_infor">
                    <span>Giá xe</span>
                    <span class="number">Số lượng</span>
                    <span class="deposit1">Số tiền cọc</span>
                    <span>Ngày đặt </span>
                </div>
            </div>

            <!-- Kiểm tra và hiển thị dữ liệu từ cơ sở dữ liệu -->
            <?php if (mysqli_num_rows($result_transactions) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result_transactions)): ?>
                    <div class="content_car">
                        <div class="content_car_img">
                            <?php
                            if($row['product_name'] == "VinFast VF3" && $row['color'] == "vàng"){  
                                $img = '../img/vf-3.jpg';
                            } 
                            else if($row['product_name'] == "VinFast VF3" && $row['color']=="đen"){  
                                $img = '../img/VF3_black.png';
                            }
                            else if($row['product_name'] == "VinFast VF3" && $row['color']=="xanh"){  
                                $img = '../img/VF3_blue.png';
                            }
                            else if($row['product_name'] == "VinFast VF3" && $row['color']=="đỏ"){  
                                $img = '../img/VF3_red.png';
                            }
                            else if($row['product_name'] == "VinFast VF3" && $row['color']=="trắng"){  
                                $img = '../img/VF3_white.png';
                            }
                            
                            else if($row['product_name'] == "VinFast VF5" && $row['color'] == "vàng"){  
                                $img = '../img/VF5_yellow.png';
                            } 
                            else if($row['product_name'] == "VinFast VF5" && $row['color']=="đen"){  
                                $img = '../img/VF5_black.png';
                            }
                            else if($row['product_name'] == "VinFast VF5" && $row['color']=="xanh"){  
                                $img = '../img/VF5_blue.png';
                            }
                            else if($row['product_name'] == "VinFast VF5" && $row['color']=="đỏ"){  
                                $img = '../img/VF5_red.png';
                            }
                            else if($row['product_name'] == "VinFast VF5" && $row['color']=="trắng"){  
                                $img = '../img/VF5_white.png';
                            }

                            else if($row['product_name'] == "VinFast VF6" && $row['color']=="đen"){  
                                $img = '../img/VF6_black.png';
                            }
                            else if($row['product_name'] == "VinFast VF6" && $row['color']=="xanh"){  
                                $img = '../img/vf-6.jpg';
                            }
                            else if($row['product_name'] == "VinFast VF6" && $row['color']=="đỏ"){  
                                $img = '../img/VF6_red.png';
                            }
                            else if($row['product_name'] == "VinFast VF6" && $row['color']=="trắng"){  
                                $img = '../img/VF6_white.png';
                            }
                            else if($row['product_name'] == "VinFast VF6" && $row['color']=="xanh lục"){  
                                $img = '../img/VF6_green.png';
                            }
                            
                            else if($row['product_name'] == "VinFast VF7" && $row['color']=="đen"){  
                                $img = '../img/vf-7.jpg';
                            }
                            else if($row['product_name'] == "VinFast VF7" && $row['color']=="xanh"){  
                                $img = '../img/VF7_blue.png';
                            }
                            else if($row['product_name'] == "VinFast VF7" && $row['color']=="đỏ"){  
                                $img = '../img/VF7_red.png';
                            }
                            else if($row['product_name'] == "VinFast VF7" && $row['color']=="trắng"){  
                                $img = '../img/VF7_white.png';
                            }
                            else if($row['product_name'] == "VinFast VF7" && $row['color']=="xanh lục"){  
                                $img = '../img/VF7_green.png';
                            }

                            else if($row['product_name'] == "VinFast VF8" && $row['color']=="trắng"){  
                                $img = '../img/vf-8.jpg';
                            } 
                            else if($row['product_name'] == "VinFast VF8" && $row['color']=="đen"){  
                                $img = '../img/VF8_black.png';
                            } 
                            
                            else if($row['product_name'] == "VinFast VF9" && $row['color']=="đen"){  
                                $img = '../img/vf-9.jpg';
                            }
                            else if($row['product_name'] == "VinFast VF9" && $row['color']=="xanh"){  
                                $img = '../img/VF9_blue.png';
                            }
                            else if($row['product_name'] == "VinFast VF9" && $row['color']=="đỏ"){  
                                $img = '../img/VF9_red.png';
                            }
                            else if($row['product_name'] == "VinFast VF9" && $row['color']=="trắng"){  
                                $img = '../img/VF9_white.png';
                            }
                            else if($row['product_name'] == "VinFast VF9" && $row['color']=="xanh lục"){  
                                $img = '../img/VF9_green.png';
                            }
                            
                            else if($row['product_name'] == "VinFast VFe34" && $row['color']=="đen"){  
                                $img = '../img/VFe34_black.png';
                            }
                            else if($row['product_name'] == "VinFast VFe34" && $row['color']=="xanh"){  
                                $img = '../img/VFe34_blue.png';
                            }
                            else if($row['product_name'] == "VinFast VFe34" && $row['color']=="đỏ"){  
                                $img = '../img/VFe34_red.png';
                            }
                            else if($row['product_name'] == "VinFast VFe34" && $row['color']=="trắng"){  
                                $img = '../img/VFe34_white.png';
                            }
                            else if($row['product_name'] == "VinFast VFe34" && $row['color']=="xanh lục"){  
                                $img = '../img/vf-e34.jpg';
                            }
                            else if($row['product_name'] == "VinFast VFe34" && $row['color']=="vàng hoàng hôn"){  
                                $img = '../img/VFe34_sunset_orange.png';
                            }
                            
                            ?>    
                            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>"> 
                        </div>
                        <div class="content_car_name"><span><?php echo htmlspecialchars($row['product_name']); ?></span></div>
                        <div class="content_car_color"><span>Màu: <?php echo htmlspecialchars($row['color']); ?></span></div>
                        <div class="content_car_price"><b><sup>đ</sup><?php echo number_format($row['product_price'], 0, ',', '.'); ?></b></div>
                        <div class="content_car_number"><span><?php echo htmlspecialchars($row['transaction_number']); ?></span></div>
                        <div class="content_car_deposit"><b><sup>đ</sup><?php echo number_format($row['deposit'], 0, ',', '.'); ?></b></div>
                        <div class="content_car_date"><?php echo date("d-m-Y",strtotime($row['transaction_date'])); ?></div>

                    </div>
                    <hr>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Không có đơn xe nào đã cọc.</p>

                
            <?php endif; ?>
                
        </div>
    </div>
    <?php include('../client/footer.php'); ?>
</body>
</html>
