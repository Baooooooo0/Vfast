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

    // Truy vấn các đơn hàng (ĐÃ CẬP NHẬT - bao gồm tất cả trạng thái mới)
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
        WHERE t.customer_id = ? AND t.transaction_status IN ('completed', 'pending', 'delivering', 'cancelled')
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

// Nếu không có dữ liệu thật, tạo dữ liệu giả (ĐÃ CẬP NHẬT - thêm đơn hàng có thể hủy)
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
    // Thêm đơn hàng đang giao (có thể hủy trong một số trường hợp)
    $transactions[] = [
        'transaction_id' => 'F3',
        'product_id' => 'VF03',
        'product_name' => 'VinFast VF 3',
        'color' => 'Xanh',
        'product_price' => 315000000,
        'image' => '../img/VF3_blue.jpg',
        'deposit' => 50000000,
        'transaction_date' => date('Y-m-d', strtotime('-1 day')),
        'transaction_number' => 1,
        'transaction_status' => 'delivering',
        'payment_method' => 'Chuyển khoản ngân hàng',
        'receiver_name' => 'Lê Văn C (Fake)',
        'receiver_phone' => '0901234599',
        'receiver_address' => '789 Đường GHI, Phường T, Quận P, TP Đà Nẵng',
        'customer_name' => 'Lê Văn C (Fake)',
        'customer_email' => 'levanc@example.com',
        'customer_phone' => '0901234599'
    ];
    // Thêm đơn hàng đã hủy
    $transactions[] = [
        'transaction_id' => 'F4',
        'product_id' => 'VF04',
        'product_name' => 'VinFast VF 6',
        'color' => 'Đen',
        'product_price' => 765000000,
        'image' => '../img/VF6_black.jpg',
        'deposit' => 100000000,
        'transaction_date' => date('Y-m-d', strtotime('-3 days')),
        'transaction_number' => 1,
        'transaction_status' => 'cancelled',
        'payment_method' => 'Thẻ tín dụng',
        'receiver_name' => 'Phạm Thị D (Fake)',
        'receiver_phone' => '0901234511',
        'receiver_address' => '456 Đường JKL, Phường U, Quận Q, TP Cần Thơ',
        'customer_name' => 'Phạm Thị D (Fake)',
        'customer_email' => 'phamthid@example.com',
        'customer_phone' => '0901234511'
    ];
    // Thêm đơn hàng chờ xử lý (có thể hủy)
    $transactions[] = [
        'transaction_id' => 'F5',
        'product_id' => 'VF05',
        'product_name' => 'VinFast VF 7',
        'color' => 'Trắng ngọc trai',
        'product_price' => 850000000,
        'image' => '../img/VF7_white.jpg',
        'deposit' => 75000000,
        'transaction_date' => date('Y-m-d'),
        'transaction_number' => 1,
        'transaction_status' => 'pending',
        'payment_method' => 'MOMO',
        'receiver_name' => 'Hoàng Văn E (Fake)',
        'receiver_phone' => '0901234522',
        'receiver_address' => '123 Đường MNO, Phường V, Quận R, TP Hải Phòng',
        'customer_name' => 'Hoàng Văn E (Fake)',
        'customer_email' => 'hoangvane@example.com',
        'customer_phone' => '0901234522'
    ];
}

mysqli_close($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đơn hàng</title>
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
        
        /* Style cho dữ liệu null/empty */
        .null-data {
            color: #999;
            font-style: italic;
        }
        
        /* Style cho dữ liệu bình thường */
        .normal-data {
            color: #333;
        }
        
        /* Style cho nút hủy đơn hàng */
        .btn-cancel-order {
            background-color: #dc3545;
            color: white;
            border: 1px solid #dc3545;
            padding: 8px 16px;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .btn-cancel-order:hover {
            background-color: #c82333;
            border-color: #bd2130;
            color: white;
        }
        
        .btn-cancel-order:disabled {
            background-color: #6c757d;
            border-color: #6c757d;
            cursor: not-allowed;
            opacity: 0.65;
        }
        
        /* Style cho đơn hàng đã hủy */
        .cancelled-order {
            opacity: 0.7;
            background-color: #f8f9fa !important;
        }
        
        .cancelled-order img {
            filter: grayscale(50%);
        }
        
        .cancelled-order .product-name {
            text-decoration: line-through;
            color: #6c757d;
        }
        
        /* Style cho đơn hàng đang giao */
        .delivering-order {
            background-color: #fff3cd !important;
            border-left: 4px solid #ff8c00;
        }
        
        .delivering-order .product-name {
            color: #856404;
            font-weight: bold;
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
                <h4 class="title_item_cart">Lịch sử đơn hàng</h4>
                <hr>
            </div>
            <div class="brand">
                <i class="fa fa-history" aria-hidden="true"></i>
                <span>Vinfast</span>
            </div>

            <!-- Bộ lọc trạng thái đơn hàng -->
            <div style="margin-bottom: 20px;">
                <label for="statusFilter" style="margin-right: 10px; font-weight: bold;">Lọc theo trạng thái:</label>
                <select id="statusFilter" onchange="filterByStatus()" style="padding: 5px 10px; border: 1px solid #ccc; border-radius: 4px;">
                    <option value="all">Tất cả đơn hàng</option>
                    <option value="completed">Đã hoàn thành</option>
                    <option value="delivering">Đang giao hàng</option>
                    <option value="pending">Chờ xử lý</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
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
                            <?php 
                            $rowClass = '';
                            if ($row['transaction_status'] === 'cancelled') {
                                $rowClass = 'cancelled-order';
                            } elseif ($row['transaction_status'] === 'delivering') {
                                $rowClass = 'delivering-order';
                            }
                            ?>
                            <tr data-toggle="modal" data-target="#orderModal<?php echo $row['transaction_id']; ?>" class="<?php echo $rowClass; ?>">
                                <td class="col-product" data-label="Sản phẩm">
                                    <img src="<?php echo !empty($row['image']) ? htmlspecialchars($row['image']) : '../img/default-car.jpg'; ?>" alt="<?php echo !empty($row['product_name']) ? htmlspecialchars($row['product_name']) : 'Sản phẩm'; ?>">
                                    <span class="product-name <?php echo !empty($row['product_name']) ? 'normal-data' : 'null-data'; ?>"><?php echo !empty($row['product_name']) ? htmlspecialchars($row['product_name']) : 'Chưa cập nhật'; ?></span>
                                </td>
                                <td class="col-color <?php echo !empty($row['color']) ? 'normal-data' : 'null-data'; ?>" data-label="Màu sắc"><?php echo !empty($row['color']) ? htmlspecialchars($row['color']) : 'Chưa cập nhật'; ?></td>
                                <td class="col-price" data-label="Giá xe"><sup>đ</sup><?php echo !empty($row['product_price']) ? number_format($row['product_price'], 0, ',', '.') : '0'; ?></td>
                                <td class="col-quantity" data-label="Số lượng"><?php echo !empty($row['transaction_number']) && $row['transaction_number'] > 0 ? htmlspecialchars($row['transaction_number']) : '1'; ?></td>
                                <td class="col-paid" data-label="Đã thanh toán"><b><sup>đ</sup><?php echo !empty($row['deposit']) ? number_format($row['deposit'], 0, ',', '.') : '0'; ?></b></td>
                                <td class="col-date" data-label="Ngày đặt"><?php echo !empty($row['transaction_date']) ? date("d-m-Y",strtotime($row['transaction_date'])) : '<span class="null-data">Chưa cập nhật</span>'; ?></td>
                                <td class="col-status" data-label="Trạng thái">
                                    <?php 
                                    switch($row['transaction_status']) {
                                        case 'completed':
                                            echo '<span style="color: green; font-weight: bold;">Đã hoàn thành</span>';
                                            break;
                                        case 'delivering':
                                            echo '<span style="color: #ff8c00; font-weight: bold;">Đang giao hàng</span>';
                                            break;
                                        case 'pending':
                                            echo '<span style="color: blue; font-weight: bold;">Chờ xử lý</span>';
                                            break;
                                        case 'cancelled':
                                            echo '<span style="color: red; font-weight: bold;">Đã hủy</span>';
                                            break;
                                        default:
                                            echo '<span style="color: gray;">Không xác định</span>';
                                    }
                                    ?>
                                </td>
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
                            <h5 class="modal-title" id="modalLabel<?php echo $row['transaction_id']; ?>">Chi tiết đơn hàng: <?php echo !empty($row['product_name']) ? htmlspecialchars($row['product_name']) : 'Sản phẩm'; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Thông tin sản phẩm</h5>
                                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" style="width: 100%; max-width: 250px; margin-bottom: 15px; border-radius: 5px;">
                                    <p><strong>Sản phẩm:</strong> <span class="<?php echo !empty($row['product_name']) ? 'normal-data' : 'null-data'; ?>"><?php echo !empty($row['product_name']) ? htmlspecialchars($row['product_name']) : 'Chưa cập nhật'; ?></span></p>
                                    <p><strong>Màu sắc:</strong> <span class="<?php echo !empty($row['color']) ? 'normal-data' : 'null-data'; ?>"><?php echo !empty($row['color']) ? htmlspecialchars($row['color']) : 'Chưa cập nhật'; ?></span></p>
                                    <p><strong>Giá xe:</strong> <?php echo !empty($row['product_price']) ? number_format($row['product_price'], 0, ',', '.') : '0'; ?> <sup>đ</sup></p>
                                    <p><strong>Số lượng:</strong> <?php echo !empty($row['transaction_number']) && $row['transaction_number'] > 0 ? htmlspecialchars($row['transaction_number']) : '1'; ?></p>
                                    <p><strong>Đã thanh toán:</strong> <?php echo !empty($row['deposit']) ? number_format($row['deposit'], 0, ',', '.') : '0'; ?> <sup>đ</sup></p>
                                    <p><strong>Ngày đặt:</strong> <span class="<?php echo !empty($row['transaction_date']) ? 'normal-data' : 'null-data'; ?>"><?php echo !empty($row['transaction_date']) ? date("d-m-Y", strtotime($row['transaction_date'])) : 'Chưa cập nhật'; ?></span></p>
                                    <p><strong>Phương thức:</strong> <span class="<?php echo !empty($row['payment_method']) ? 'normal-data' : 'null-data'; ?>"><?php echo !empty($row['payment_method']) ? htmlspecialchars($row['payment_method']) : 'Chưa cập nhật'; ?></span></p>
                                    
                                    <?php if ($row['transaction_status'] === 'cancelled'): ?>
                                        <hr style="border-color: #dc3545;">
                                        <h6 style="color: #dc3545; font-weight: bold;">
                                            <i class="fa fa-times-circle"></i> Đơn hàng đã bị hủy
                                        </h6>
                                        <p style="color: #6c757d; font-style: italic;">
                                            Đơn hàng này đã được hủy. Tiền đặt cọc sẽ được hoàn lại trong vòng 3-5 ngày làm việc.
                                        </p>
                                    <?php elseif ($row['transaction_status'] === 'delivering'): ?>
                                        <hr style="border-color: #ff8c00;">
                                        <h6 style="color: #ff8c00; font-weight: bold;">
                                            <i class="fa fa-truck"></i> Đơn hàng đang được giao
                                        </h6>
                                        <p style="color: #6c757d; font-style: italic;">
                                            Xe của bạn đang được vận chuyển đến địa chỉ nhận. Vui lòng chờ cuộc gọi từ nhân viên giao hàng.
                                        </p>
                                    <?php elseif ($row['transaction_status'] === 'pending'): ?>
                                        <hr style="border-color: #007bff;">
                                        <h6 style="color: #007bff; font-weight: bold;">
                                            <i class="fa fa-clock-o"></i> Đơn hàng chờ xử lý
                                        </h6>
                                        <p style="color: #6c757d; font-style: italic;">
                                            Đơn hàng đang chờ được xác nhận và xử lý. Bạn có thể hủy đơn hàng nếu cần.
                                        </p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6">
                                    <h5>Thông tin khách hàng</h5>
                                    <p><strong>Họ tên:</strong> <span class="<?php echo !empty($row['customer_name']) ? 'normal-data' : 'null-data'; ?>"><?php echo !empty($row['customer_name']) ? htmlspecialchars($row['customer_name']) : 'Chưa cập nhật'; ?></span></p>
                                    <p><strong>Email:</strong> <span class="<?php echo !empty($row['customer_email']) ? 'normal-data' : 'null-data'; ?>"><?php echo !empty($row['customer_email']) ? htmlspecialchars($row['customer_email']) : 'Chưa cập nhật'; ?></span></p>
                                    <p><strong>Số điện thoại:</strong> <span class="<?php echo !empty($row['customer_phone']) ? 'normal-data' : 'null-data'; ?>"><?php echo !empty($row['customer_phone']) ? htmlspecialchars($row['customer_phone']) : 'Chưa cập nhật'; ?></span></p>
                                    
                                    <hr>
                                    
                                    <h5>Thông tin nhận xe</h5>
                                    <p><strong>Người nhận:</strong> <span class="<?php echo !empty($row['receiver_name']) ? 'normal-data' : 'null-data'; ?>"><?php echo !empty($row['receiver_name']) ? htmlspecialchars($row['receiver_name']) : 'Chưa cập nhật'; ?></span></p>
                                    <p><strong>SĐT nhận:</strong> <span class="<?php echo !empty($row['receiver_phone']) ? 'normal-data' : 'null-data'; ?>"><?php echo !empty($row['receiver_phone']) ? htmlspecialchars($row['receiver_phone']) : 'Chưa cập nhật'; ?></span></p>
                                    <p><strong>Địa chỉ nhận:</strong> <span class="<?php echo !empty($row['receiver_address']) ? 'normal-data' : 'null-data'; ?>"><?php echo !empty($row['receiver_address']) ? htmlspecialchars($row['receiver_address']) : 'Chưa cập nhật'; ?></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <?php if ($row['transaction_status'] === 'pending'): ?>
                                <button type="button" class="btn btn-cancel-order" onclick="cancelOrder('<?php echo $row['transaction_id']; ?>')">
                                    <i class="fa fa-times"></i> Hủy đơn hàng
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- JavaScript để xử lý hủy đơn hàng và bộ lọc -->
    <script>
    // Hàm lọc theo trạng thái
    function filterByStatus() {
        const filter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.history-table tbody tr');
        
        rows.forEach(row => {
            const statusCell = row.querySelector('.col-status');
            let shouldShow = true;
            
            if (filter !== 'all') {
                const statusText = statusCell.textContent.toLowerCase().trim();
                
                switch(filter) {
                    case 'completed':
                        shouldShow = statusText.includes('đã hoàn thành');
                        break;
                    case 'delivering':
                        shouldShow = statusText.includes('đang giao hàng');
                        break;
                    case 'pending':
                        shouldShow = statusText.includes('chờ xử lý');
                        break;
                    case 'cancelled':
                        shouldShow = statusText.includes('đã hủy');
                        break;
                }
            }
            
            row.style.display = shouldShow ? '' : 'none';
        });
        
        // Cập nhật số lượng hiển thị
        updateDisplayCount();
    }
    
    // Cập nhật số lượng đơn hàng hiển thị
    function updateDisplayCount() {
        const visibleRows = document.querySelectorAll('.history-table tbody tr:not([style*="display: none"])');
        const totalRows = document.querySelectorAll('.history-table tbody tr').length;
        
        // Có thể thêm hiển thị số lượng ở đây nếu cần
        console.log(`Hiển thị ${visibleRows.length}/${totalRows} đơn hàng`);
    }
    
    function cancelOrder(transactionId) {
        if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
            // Hiển thị loading
            const cancelBtn = event.target;
            const originalText = cancelBtn.innerHTML;
            cancelBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang xử lý...';
            cancelBtn.disabled = true;
            
            // Gửi request hủy đơn hàng
            fetch('cancel_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'transaction_id=' + encodeURIComponent(transactionId)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đơn hàng đã được hủy thành công!');
                    // Reload trang để cập nhật trạng thái
                    window.location.reload();
                } else {
                    alert('Lỗi: ' + data.message);
                    // Khôi phục nút
                    cancelBtn.innerHTML = originalText;
                    cancelBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi hủy đơn hàng. Vui lòng thử lại.');
                // Khôi phục nút
                cancelBtn.innerHTML = originalText;
                cancelBtn.disabled = false;
            });
        }
    }
    </script>
    </body>
</html>