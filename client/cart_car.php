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

$transactions = []; // Mảng để lưu trữ dữ liệu giao dịch (thật hoặc giả)
$has_real_data = false; // Cờ để kiểm tra có dữ liệu thật không

if (isset($_SESSION['user_id'])) { // Kiểm tra user_id
    $user_id = $_SESSION['user_id']; // Lấy user_id từ session

    // Truy vấn các đơn hàng đã hoàn thành (ĐÃ CẬP NHẬT)
    $stmt_trans = $data->prepare("
        SELECT
            t.transaction_id,
            p.product_id,
            p.product_name,
            p.color,
            p.product_price,
            p.image,
            t.deposit,
            t.transaction_date,
            t.transaction_number,
            t.transaction_status,
            t.payment_method,
            
            -- Thông tin nhận xe (từ bảng transactions)
            t.receiver_name,
            t.receiver_phone,
            t.receiver_address,
            
            -- Thông tin khách hàng (từ bảng users)
            u.name AS customer_name,
            u.email AS customer_email,
            u.phone AS customer_phone
            
        FROM transactions t
        INNER JOIN product p ON p.product_id = t.product_id
        INNER JOIN users u ON u.id = t.customer_id
        WHERE t.customer_id = ? AND t.transaction_status = 'completed'
        ORDER BY t.transaction_date DESC
    ");

    $stmt_trans->bind_param("i", $user_id);
    $stmt_trans->execute();
    $result_transactions = $stmt_trans->get_result();

    if ($result_transactions->num_rows > 0) {
        $has_real_data = true;
        while($row = $result_transactions->fetch_assoc()) {
            $transactions[] = $row;
        }
    }
    $stmt_trans->close();

} else {
    // Chuyển hướng nếu chưa đăng nhập
    header("Location: login.php");
    exit();
}

// Nếu không có dữ liệu thật, tạo dữ liệu giả (ĐÃ CẬP NHẬT)
if (!$has_real_data) {
    $transactions[] = [
        'transaction_id' => 'F1', // ID giả
        'product_id' => 'VF06',
        'product_name' => 'VinFast VF 8',
        'color' => 'Trắng',
        'product_price' => 1100000000,
        'image' => '../img/vf-8.jpg',
        'deposit' => 1100000000,
        'transaction_date' => date('Y-m-d'),
        'transaction_number' => 1,
        'transaction_status' => 'completed',
        'payment_method' => 'Chuyển khoản ngân hàng',
        'receiver_name' => 'Nguyễn Văn A (Fake)',
        'receiver_phone' => '0901234567',
        'receiver_address' => '123 Đường ABC, Phường X, Quận Y, TP HCM',
        'customer_name' => 'Nguyễn Văn A (Fake)',
        'customer_email' => 'nguyenvana@example.com',
        'customer_phone' => '0901234567'
    ];
     $transactions[] = [
        'transaction_id' => 'F2', // ID giả
        'product_id' => 'VF02',
        'product_name' => 'VinFast VF 5',
        'color' => 'Đỏ',
        'product_price' => 538000000,
        'image' => '../img/VF5_red.png',
        'deposit' => 15000000,
        'transaction_date' => date('Y-m-d', strtotime('-5 days')),
        'transaction_number' => 1,
        'transaction_status' => 'completed',
        'payment_method' => 'MOMO',
        'receiver_name' => 'Trần Thị B (Fake)',
        'receiver_phone' => '0901234588',
        'receiver_address' => '456 Đường DEF, Phường Z, Quận K, TP Hà Nội',
        'customer_name' => 'Trần Thị B (Fake)',
        'customer_email' => 'tranthib@example.com',
        'customer_phone' => '0901234588'
    ];
}

mysqli_close($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử mua hàng</title>
    <?php include('home_css.php'); ?>
    <style>
        .container_cart {
            padding: 0px 30px;
            padding-top: 35px;
            padding-bottom: 50px; /* Thêm padding dưới */
        }
        .history-table {
            width: 100%;
            border-collapse: collapse; /* Gộp đường viền */
            margin-top: 15px;
        }
        .history-table th, .history-table td {
            padding: 12px 8px; /* Padding thống nhất */
            border-bottom: 1px solid #eee;
            font-size: 14px;
            vertical-align: middle; /* Căn giữa theo chiều dọc */
        }
        .history-table thead th {
            font-weight: bold;
            color: #555;
            border-bottom: 2px solid #ccc;
            text-align: left; /* Căn trái tiêu đề mặc định */
        }

        /* Thêm hiệu ứng khi hover và con trỏ chuột */
        .history-table tbody tr:hover {
            background-color: #f5f5f5;
            cursor: pointer;
        }

        /* Thêm style cho modal */
        .modal-body h5 {
            font-weight: bold;
            color: #007bff;
            margin-top: 10px;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .modal-body p {
            margin-bottom: 8px;
        }
        /* Hết style modal */


        /* Căn chỉnh cụ thể cho các cột header */
        .history-table th.col-product { width: 32%; }
        .history-table th.col-color { width: 13%; text-align: left;} /* Căn trái */
        .history-table th.col-price { width: 13%; text-align: right; } /* Căn phải */
        .history-table th.col-quantity { width: 8%; text-align: center; } /* Căn giữa */
        .history-table th.col-paid { width: 13%; text-align: right; } /* Căn phải */
        .history-table th.col-date { width: 9%; text-align: center; } /* Căn giữa */
        .history-table th.col-status { width: 12%; text-align: center; } /* Căn giữa */

         /* Căn chỉnh cụ thể cho các cột data */
        .history-table td.col-product { display: flex; align-items: center; }
        .history-table td.col-color { text-align: left; } /* Căn trái */
        .history-table td.col-price { text-align: right; } /* Căn phải */
        .history-table td.col-quantity { text-align: center; } /* Căn giữa */
        .history-table td.col-paid { text-align: right; font-weight: bold; } /* Căn phải, in đậm */
        .history-table td.col-date { text-align: center; } /* Căn giữa */
        .history-table td.col-status { text-align: center; color: green; font-weight: bold; } /* Căn giữa */


        .history-table img {
            width: 70px;
            height: auto;
            margin-right: 15px;
            object-fit: contain;
        }
         .history-table .product-name{
              font-weight: bold;
         }
         .brand i, .brand > span {
              display: inline-block;
              margin-bottom: 5px; /* Giảm margin bottom */
              padding-left: 10px;
         }

        /* Responsive - Biến table thành block trên mobile */
        @media (max-width: 768px) {
            .history-table thead {
                display: none; /* Ẩn header table */
            }
            .history-table, .history-table tbody, .history-table tr, .history-table td {
                display: block; /* Biến thành block */
                width: 100%;
            }
            .history-table tr {
                margin-bottom: 15px; /* Khoảng cách giữa các item */
                 border: 1px solid #eee; /* Thêm border cho từng item */
                 padding: 10px;
                 box-sizing: border-box;
            }
            .history-table td {
                text-align: right; /* Căn phải dữ liệu */
                padding-left: 50%; /* Tạo không gian cho label */
                position: relative;
                border-bottom: none; /* Bỏ border dưới của td */
                 padding-top: 5px;
                 padding-bottom: 5px;
            }
            .history-table td::before {
                content: attr(data-label); /* Lấy nội dung từ data-label */
                position: absolute;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left; /* Căn trái label */
                font-weight: bold;
                color: #555;
            }
             /* Style riêng cho cột product trên mobile */
             .history-table td.col-product {
                 padding-left: 0; /* Reset padding */
                 text-align: left; /* Căn trái */
                 display: flex; /* Giữ flex */
                 align-items: center;
                 margin-bottom: 10px;
             }
             .history-table td.col-product::before {
                 display: none; /* Không cần label cho cột product */
             }
             .history-table td.col-status {
                 color: green; /* Giữ màu xanh */
             }
              .history-table img { width: 80px; }
              .container_cart { padding: 0px 15px; padding-top: 20px;}
              .brand i, .brand > span { padding-left: 0;} /* Reset padding */
        }

    </style>
</head>
<body>
    <?php include('header.php'); ?>
    <div class="container-car">
        <div class="container_cart">
            <div class="title_cart">
                <h4 class="title_item_cart">Lịch sử mua hàng</h4>
                <hr>
            </div>
            <div class="brand">
                <i class="fa fa-history" aria-hidden="true"></i>
                <span>Vinfast</span>
            </div>

            <?php if (!empty($transactions)): ?>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th class="col-product">Sản phẩm</th>
                            <th class="col-color">Màu sắc</th>
                            <th class="col-price">Giá xe</th>
                            <th class="col-quantity">Số lượng</th>
                            <th class="col-paid">Đã thanh toán</th>
                            <th class="col-date">Ngày đặt</th>
                            <th class="col-status">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($transactions as $row): ?>
                            <tr data-toggle="modal" data-target="#orderModal<?php echo $row['transaction_id']; ?>">
                                <td class="col-product" data-label="Sản phẩm">
                                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                                    <span class="product-name"><?php echo htmlspecialchars($row['product_name']); ?></span>
                                </td>
                                <td class="col-color" data-label="Màu sắc"><?php echo htmlspecialchars($row['color']); ?></td>
                                <td class="col-price" data-label="Giá xe"><sup>đ</sup><?php echo number_format($row['product_price'], 0, ',', '.'); ?></td>
                                <td class="col-quantity" data-label="Số lượng"><?php echo htmlspecialchars($row['transaction_number']); ?></td>
                                <td class="col-paid" data-label="Đã thanh toán"><b><sup>đ</sup><?php echo number_format($row['deposit'], 0, ',', '.'); ?></b></td>
                                <td class="col-date" data-label="Ngày đặt"><?php echo date("d-m-Y",strtotime($row['transaction_date'])); ?></td>
                                <td class="col-status" data-label="Trạng thái">Đã hoàn thành</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; margin-top: 20px; padding-bottom: 30px;">Bạn chưa có lịch sử mua hàng thành công nào.</p>
            <?php endif; ?>

        </div>
    </div>
    <?php include('../client/footer.php'); ?>

    <?php if (!empty($transactions)): ?>
        <?php foreach($transactions as $row): ?>
            <div class="modal fade" id="orderModal<?php echo $row['transaction_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalLabel<?php echo $row['transaction_id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel<?php echo $row['transaction_id']; ?>">Chi tiết đơn hàng: <?php echo htmlspecialchars($row['product_name']); ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Thông tin sản phẩm</h5>
                                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" style="width: 100%; max-width: 250px; margin-bottom: 15px; border-radius: 5px;">
                                    <p><strong>Sản phẩm:</strong> <?php echo htmlspecialchars($row['product_name']); ?></p>
                                    <p><strong>Màu sắc:</strong> <?php echo htmlspecialchars($row['color']); ?></p>
                                    <p><strong>Giá xe:</strong> <?php echo number_format($row['product_price'], 0, ',', '.'); ?> <sup>đ</sup></p>
                                    <p><strong>Số lượng:</strong> <?php echo htmlspecialchars($row['transaction_number']); ?></p>
                                    <p><strong>Đã thanh toán:</strong> <?php echo number_format($row['deposit'], 0, ',', '.'); ?> <sup>đ</sup></p>
                                    <p><strong>Ngày đặt:</strong> <?php echo date("d-m-Y", strtotime($row['transaction_date'])); ?></p>
                                    <p><strong>Phương thức:</strong> <?php echo htmlspecialchars($row['payment_method']); ?></p>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5>Thông tin khách hàng</h5>
                                    <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($row['customer_name']); ?></p>
                                    <p><strong>Email:</strong> <?php echo htmlspecialchars($row['customer_email']); ?></p>
                                    <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($row['customer_phone']); ?></p>
                                    
                                    <hr>
                                    
                                    <h5>Thông tin nhận xe</h5>
                                    <p><strong>Người nhận:</strong> <?php echo htmlspecialchars($row['receiver_name']); ?></p>
                                    <p><strong>SĐT nhận:</strong> <?php echo htmlspecialchars($row['receiver_phone']); ?></p>
                                    <p><strong>Địa chỉ nhận:</strong> <?php echo htmlspecialchars($row['receiver_address']); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    </body>
</html>