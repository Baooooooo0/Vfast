<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu JSON từ body request
    $jsonData = file_get_contents('php://input');
    $likeCars = json_decode($jsonData, true);

    // Xử lý dữ liệu trực tiếp (không lưu vào session)
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => 'Dữ liệu đã được nhận']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xe yêu thích - VinFast</title>
    <?php include('home_css.php'); ?>
    <style>
        .container {
            padding-top: 100px;
            min-height: 100vh;
        }
        
        .title-list-car {
            text-align: center;
            margin: 40px 0;
        }
        
        .title-list-car h2 {
            color: #0071bc;
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .oto-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
            padding: 0 20px;
        }
        
        .col-5 {
            flex: 0 0 calc(20% - 16px);
            position: relative;
        }
        
        @media (max-width: 1200px) {
            .col-5 {
                flex: 0 0 calc(25% - 15px);
            }
        }
        
        @media (max-width: 992px) {
            .col-5 {
                flex: 0 0 calc(33.333% - 14px);
            }
        }
        
        @media (max-width: 768px) {
            .col-5 {
                flex: 0 0 calc(50% - 10px);
            }
        }
        
        @media (max-width: 480px) {
            .col-5 {
                flex: 0 0 100%;
            }
            .oto-list {
                padding: 0 10px;
            }
        }
    </style>
</head>

<body>
    <?php include('header.php') ?>
    <div class="container">
        <div class="title-list-car">
            <h2>Danh sách xe đã thích </h2>
        </div>
        <section class="oto-list">
            <script>
                // Lấy dữ liệu likeCars từ localStorage với user-specific key
                const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '0'; ?>;
                const storageKey = 'likeCars_' + currentUserId;
                let likeCars = JSON.parse(localStorage.getItem(storageKey)) || [];

                if (likeCars.length > 0) {
                    likeCars.forEach(row => {
                        const colDiv = document.createElement('div');
                        colDiv.className = 'col-5';
                        // 🩶 Thêm trái tim giống oto.php
                        const smallCard = document.createElement('div');
                        smallCard.className = 'small_card';
                        smallCard.innerHTML = `
                            <i class="fas fa-heart like_car"
                                data-id="${row.product_id}"
                                data-image="${row.image}"
                                data-name="${row.name}"
                                data-color="${row.color}"
                                data-stock="${row.stock}"
                                style="color:red; cursor:pointer;"
                                title="Bỏ yêu thích">
                            </i>
                        `;
                        colDiv.appendChild(smallCard);

                        const otoItemDiv = document.createElement('div');
                        otoItemDiv.className = 'oto-item';

                        // Check sold out
                        if (row.stock == 0) {
                            const soldOutDiv = document.createElement('div');
                            soldOutDiv.className = 'sold-out';
                            colDiv.appendChild(soldOutDiv);
                        }

                        // Hình ảnh
                        const img = document.createElement('img');
                        img.src = row.image;
                        img.alt = row.name;

                        // Tên xe
                        const h3 = document.createElement('h3');
                        h3.textContent = row.name;

                        // Màu xe
                        const colorP = document.createElement('p');
                        colorP.textContent = 'Màu: ' + row.color;

                        // Số lượng tồn kho
                        const stockP = document.createElement('p');
                        stockP.textContent = 'Số lượng tồn kho: ' + row.stock;

                        // Nút xem chi tiết
                        const detailsBtn = document.createElement('button');
                        detailsBtn.className = 'details';
                        detailsBtn.innerHTML = `<a href="detail.php?product_id=${row.product_id}">Xem chi tiết</a>`;

                        otoItemDiv.appendChild(img);
                        otoItemDiv.appendChild(h3);
                        otoItemDiv.appendChild(colorP);
                        otoItemDiv.appendChild(stockP);
                        otoItemDiv.appendChild(detailsBtn);

                        colDiv.appendChild(otoItemDiv);

                        document.querySelector('.oto-list').appendChild(colDiv);
                    });
                } else {
                    // Hiển thị thông báo trống
                    document.querySelector('.oto-list').innerHTML = `
                        <div class="empty-list" style="
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            min-height: 60vh;
                            text-align: center;
                            padding: 80px 20px;
                            color: #666;
                            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                            border-radius: 20px;
                            margin: 40px auto;
                            max-width: 600px;
                            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                            border: 1px solid #e2e8f0;
                        ">
                            <div style="margin-bottom: 30px;">
                                <i class="fas fa-heart-broken" style="
                                    font-size: 80px;
                                    color: #cbd5e0;
                                    margin-bottom: 20px;
                                    display: block;
                                    animation: pulse 2s infinite;
                                "></i>
                            </div>
                            <h3 style="
                                color: #4a5568;
                                font-size: 28px;
                                font-weight: 600;
                                margin: 20px 0 15px 0;
                                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                            ">Chưa có xe nào được yêu thích</h3>
                            <p style="
                                color: #718096;
                                font-size: 16px;
                                margin: 15px 0 30px 0;
                                line-height: 1.6;
                            ">
                                Hãy khám phá bộ sưu tập xe điện VinFast<br>
                                và thêm những chiếc xe yêu thích của bạn!
                            </p>
                            <a href="oto.php" style="
                                display: inline-flex;
                                align-items: center;
                                gap: 8px;
                                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                color: white;
                                text-decoration: none;
                                padding: 15px 30px;
                                border-radius: 50px;
                                font-weight: 600;
                                font-size: 16px;
                                transition: all 0.3s ease;
                                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                            " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.6)';" 
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.4)';">
                                <i class="fas fa-car" style="font-size: 18px;"></i>
                                Khám phá xe điện
                            </a>
                            <style>
                                @keyframes pulse {
                                    0% { opacity: 0.6; transform: scale(1); }
                                    50% { opacity: 1; transform: scale(1.05); }
                                    100% { opacity: 0.6; transform: scale(1); }
                                }
                                .oto-list {
                                    display: flex !important;
                                    align-items: center !important;
                                    justify-content: center !important;
                                    min-height: 70vh !important;
                                }
                            </style>
                        </div>
                    `;
                }
            </script>
        </section>
    </div>
    <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <i class="modal-icon fas fa-pen"></i>
                    <h4 class="modal-title">Thông tin chủ xe</h4>
                    <button type="button" class="close" data-dismiss="modal">X</button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Combined Form -->
                    <form action="../client/process_cart.php" id="combinedForm" method="POST">
                        <!-- Form Section 1 -->
                        <div class="warningMessage">
                            <span id="contetnt-warning"></span>
                        </div>
                        <div class="form-section active">
                            <div class="form-group">
                                <i class="fa fa-user"></i>
                                <input type="text" class="form-control" placeholder="Họ và tên" name="name" required>
                            </div>
                            <div class="form-group">
                                <i class="fa fa-phone"></i>
                                <input type="text" class="form-control" placeholder="Số điện thoại" name="phone" required>
                            </div>
                            <div class="form-group">
                                <i class="fa fa-envelope"></i>
                                <input type="email" class="form-control" placeholder="Email" name="email" required>
                            </div>
                            <div class="form-group">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.8333 12.5H14.1667V10.8333H15.8333V12.5ZM15.8333 15.8333H14.1667V14.1666H15.8333V15.8333ZM10.8333 5.83329H9.16667V4.16663H10.8333V5.83329ZM10.8333 9.16663H9.16667V7.49996H10.8333V9.16663ZM10.8333 12.5H9.16667V10.8333H10.8333V12.5ZM10.8333 15.8333H9.16667V14.1666H10.8333V15.8333ZM5.83333 9.16663H4.16667V7.49996H5.83333V9.16663ZM5.83333 12.5H4.16667V10.8333H5.83333V12.5ZM5.83333 15.8333H4.16667V14.1666H5.83333V15.8333ZM12.5 9.16663V4.16663L10 1.66663L7.5 4.16663V5.83329H2.5V17.5H17.5V9.16663H12.5Z" fill="#8a8a8a"></path>
                                </svg>
                                <select name="place" id="placeSelect" class="form-control" required>
                                    <option value="" disabled selected hidden>Tỉnh thành</option>
                                    <?php foreach ($places as $place): ?>
                                        <option value="<?php echo htmlspecialchars($place); ?>"><?php echo htmlspecialchars($place); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.0002 1.66663C12.7585 1.66663 15.0002 3.88329 15.0002 6.62496C15.0002 10.3416 10.0002 15.8333 10.0002 15.8333C10.0002 15.8333 5.00016 10.3416 5.00016 6.62496C5.00016 3.88329 7.24183 1.66663 10.0002 1.66663ZM10.0002 4.99996C9.55814 4.99996 9.13421 5.17555 8.82165 5.48811C8.50909 5.80067 8.3335 6.2246 8.3335 6.66663C8.3335 7.10865 8.50909 7.53258 8.82165 7.84514C9.13421 8.1577 9.55814 8.33329 10.0002 8.33329C10.4422 8.33329 10.8661 8.1577 11.1787 7.84514C11.4912 7.53258 11.6668 7.10865 11.6668 6.66663C11.6668 6.2246 11.4912 5.80067 11.1787 5.48811C10.8661 5.17555 10.4422 4.99996 10.0002 4.99996ZM16.6668 15.8333C16.6668 17.675 13.6835 19.1666 10.0002 19.1666C6.31683 19.1666 3.3335 17.675 3.3335 15.8333C3.3335 14.7583 4.35016 13.8 5.92516 13.1916L6.4585 13.95C5.5585 14.325 5.00016 14.8416 5.00016 15.4166C5.00016 16.5666 7.24183 17.5 10.0002 17.5C12.7585 17.5 15.0002 16.5666 15.0002 15.4166C15.0002 14.8416 14.4418 14.325 13.5418 13.95L14.0752 13.1916C15.6502 13.8 16.6668 14.7583 16.6668 15.8333Z" fill="#8a8a8a"></path>
                                </svg>
                                <select name="showroom" id="showroomSelect" class="form-control" required>
                                    <option value="" disabled selected hidden>Showroom nhận xe</option>
                                </select>
                            </div>
                            <div class="form-navigation1">
                                <button type="button" class="btn btn-primary next-btn">Bước kế tiếp</button>
                            </div>
                        </div>

                        <!-- Form Section 2 -->
                        <div class="warningMessage">
                            <span id="contetnt-warning"></span>
                        </div>
                        <div class="form-section">
                            <div class="form-group">
                                <select name="product_name" id="Select_product" class="form-control" required>
                                    <option value="" disabled selected hidden>Tên sản phẩm</option>
                                    <?php foreach ($products as $product): ?>
                                        <option value="<?php echo htmlspecialchars($product); ?>"><?php echo htmlspecialchars($product); ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="color" id="showColor" class="form-control" required>
                                    <option value="" disabled selected hidden>Màu xe</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="number" placeholder="Số lượng mua xe" name="transaction_number" min="1" max="10">
                            </div>
                            <div class="form-group">
                                <b>Giá xe : </b>&nbsp;<span id="price-display"></span>
                            </div>
                            <div class="form-navigation">
                                <button type="button" class="btn btn-primary prev-btn">Quay lại</button>
                                <button type="button" class="btn btn-primary next-btn">Bước kế tiếp</button>
                            </div>
                        </div>

                        <!-- Form Section 3 -->
                        <div class="warningMessage">
                            <span id="content-warning"></span>
                        </div>
                        <div class="form-section">
                            <div class="form-group">
                                <span>Đặt cọc trước <b>10%</b>&nbsp;&rarr;&nbsp;<b>15,000,000 VND</b></span>
                            </div>
                            <div class="form-group">
                                <select name="payment_method" class="form-control" required>
                                    <option value="" disabled selected hidden>Phương thức thanh toán</option>
                                    <option value="Tiền mặt">Tiền mặt</option>
                                    <option value="Chuyển khoản">Chuyển khoản</option>
                                    <option value="Trả góp">Trả góp</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <span>Quét mã QR tại đây để thanh toán: </span>
                            </div>
                            <div class="form-group">
                                <img src="../img/qr_code.png" alt="qr_code">
                            </div>

                            <div class="form-navigation">
                                <button type="button" class="btn btn-primary prev-btn">Quay lại</button>
                                <button type="submit" class="btn btn-primary submit-btn">Gửi</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php') ?>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.querySelector('.oto-list');
        const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '0'; ?>;
        const storageKey = 'likeCars_' + currentUserId;
        let likeCars = JSON.parse(localStorage.getItem(storageKey)) || [];

        container.addEventListener('click', function(e) {
            if (e.target.classList.contains('like_car')) {
                const btn = e.target;
                const product = {
                    product_id: btn.getAttribute('data-id'),
                    image: btn.getAttribute('data-image'),
                    name: btn.getAttribute('data-name'),
                    color: btn.getAttribute('data-color'),
                    stock: btn.getAttribute('data-stock')
                };

                toggleLike(product, btn);
            }
        });

        function toggleLike(product, btn) {
            // Tìm theo name và color để tránh trùng product_id
            const index = likeCars.findIndex(p => p.name === product.name && p.color === product.color);
            if (index !== -1) {
                // Unlike: xóa khỏi danh sách và DOM
                likeCars.splice(index, 1);

                // Tìm và xóa phần tử xe khỏi DOM
                const carElement = btn.closest('.col-5');
                if (carElement) {
                    carElement.remove();
                }

                // Hiển thị thông báo
                showNotification('Đã bỏ xe khỏi danh sách yêu thích', 'warning');

                // Kiểm tra nếu không còn xe nào, hiển thị thông báo trống
                checkEmptyList();
            } else {
                // Like: thêm vào danh sách (case này ít xảy ra trong liked_car.php)
                likeCars.push(product);
                btn.style.color = 'red';
                btn.title = 'Bỏ yêu thích';

                // Hiển thị thông báo
                showNotification('Đã thêm xe vào danh sách yêu thích', 'success');
            }
            localStorage.setItem(storageKey, JSON.stringify(likeCars));
            updateCountHeart();

            // Trigger event để notify header
            window.dispatchEvent(new CustomEvent('likeCountChanged', {
                detail: {
                    count: likeCars.length
                }
            }));
        }

        function checkEmptyList() {
            const otoList = document.querySelector('.oto-list');
            const existingCars = otoList.querySelectorAll('.col-5');

            if (existingCars.length === 0) {
                otoList.innerHTML = `
                <div class="empty-list" style="
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            min-height: 60vh;
                            text-align: center;
                            padding: 80px 20px;
                            color: #666;
                            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                            border-radius: 20px;
                            margin: 40px auto;
                            max-width: 600px;
                            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                            border: 1px solid #e2e8f0;
                        ">
                            <div style="margin-bottom: 30px;">
                                <i class="fas fa-heart-broken" style="
                                    font-size: 80px;
                                    color: #cbd5e0;
                                    margin-bottom: 20px;
                                    display: block;
                                    animation: pulse 2s infinite;
                                "></i>
                            </div>
                            <h3 style="
                                color: #4a5568;
                                font-size: 28px;
                                font-weight: 600;
                                margin: 20px 0 15px 0;
                                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                            ">Chưa có xe nào được yêu thích</h3>
                            <p style="
                                color: #718096;
                                font-size: 16px;
                                margin: 15px 0 30px 0;
                                line-height: 1.6;
                            ">
                                Hãy khám phá bộ sưu tập xe điện VinFast<br>
                                và thêm những chiếc xe yêu thích của bạn!
                            </p>
                            <a href="oto.php" style="
                                display: inline-flex;
                                align-items: center;
                                gap: 8px;
                                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                color: white;
                                text-decoration: none;
                                padding: 15px 30px;
                                border-radius: 50px;
                                font-weight: 600;
                                font-size: 16px;
                                transition: all 0.3s ease;
                                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                            " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.6)';" 
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.4)';">
                                <i class="fas fa-car" style="font-size: 18px;"></i>
                                Khám phá xe điện
                            </a>
                            <style>
                                @keyframes pulse {
                                    0% { opacity: 0.6; transform: scale(1); }
                                    50% { opacity: 1; transform: scale(1.05); }
                                    100% { opacity: 0.6; transform: scale(1); }
                                }
                                .oto-list {
                                    display: flex !important;
                                    align-items: center !important;
                                    justify-content: center !important;
                                    min-height: 70vh !important;
                                }
                            </style>
                        </div>
                   
                `;
            }
        }

        function showNotification(message, type = 'success') {
            // Xóa thông báo cũ nếu có
            const existingNotification = document.querySelector('.like-notification');
            if (existingNotification) {
                existingNotification.remove();
            }

            const notification = document.createElement('div');
            notification.className = 'like-notification';
            notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#28a745' : type === 'warning' ? '#ffc107' : '#dc3545'};
            color: ${type === 'warning' ? '#333' : 'white'};
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            font-size: 14px;
            max-width: 300px;
            animation: slideIn 0.3s ease-out;
        `;

            // Thêm animation CSS nếu chưa có
            if (!document.querySelector('#notification-styles')) {
                const style = document.createElement('style');
                style.id = 'notification-styles';
                style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
                document.head.appendChild(style);
            }

            notification.textContent = message;
            document.body.appendChild(notification);

            // Tự động xóa sau 3 giây
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-in';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        function updateCountHeart() {
            const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '0'; ?>;
            const storageKey = 'likeCars_' + currentUserId;
            const likeProducts = JSON.parse(localStorage.getItem(storageKey)) || [];
            const countElement = document.querySelector('.count-heart');
            if (countElement) {
                if (likeProducts.length > 0) {
                    countElement.textContent = likeProducts.length;
                    countElement.style.display = 'flex';
                    countElement.style.visibility = 'visible';
                } else {
                    countElement.textContent = '';
                    countElement.style.display = 'none';
                    countElement.style.visibility = 'hidden';
                }
            }

            // Cập nhật cả header counter (nếu có header function)
            if (typeof window.updateCountHeart === 'function') {
                window.updateCountHeart();
            }
        }

        function updateLikeStates() {
            const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '0'; ?>;
            const storageKey = 'likeCars_' + currentUserId;
            const likeProducts = JSON.parse(localStorage.getItem(storageKey)) || [];
            const likeButtons = document.querySelectorAll('.like_car');
            
            likeButtons.forEach(btn => {
                const productName = btn.getAttribute('data-name');
                const productColor = btn.getAttribute('data-color');
                const isLiked = likeProducts.some(car => car.name === productName && car.color === productColor);
                
                if (isLiked) {
                    btn.style.color = 'red';
                    btn.title = 'Bỏ yêu thích';
                } else {
                    btn.style.color = '#ccc';
                    btn.title = 'Thêm vào yêu thích';
                }
            });
        }

        // Cập nhật trạng thái khi trang load
        updateCountHeart();
        updateLikeStates();

        // Đảm bảo header counter cũng được cập nhật
        setTimeout(() => {
            const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '0'; ?>;
            const storageKey = 'likeCars_' + currentUserId;
            const likeProducts = JSON.parse(localStorage.getItem(storageKey)) || [];
            // Trigger custom event để notify header
            window.dispatchEvent(new CustomEvent('likeCountChanged', {
                detail: {
                    count: likeProducts.length
                }
            }));
        }, 100);
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        function initForm() {
            const formSections = document.querySelectorAll(".form-section");
            const nextBtns = document.querySelectorAll(".next-btn");
            const prevBtns = document.querySelectorAll(".prev-btn");
            let currentForm = 0;

            function showForm() {
                formSections.forEach((section, index) => {
                    section.style.display = 'none';
                });
                formSections[currentForm].style.display = 'block';
            }

            function validateCurrentForm() {
                const currentSectionInputs = formSections[currentForm].querySelectorAll('input, select');
                // console.log("Validating Form Section: ", currentForm);
                let isValid = true;

                currentSectionInputs.forEach(function(input) {
                    // console.log("Input Value: ", input.value.trim());
                    if (input.value.trim() === "") {
                        isValid = false;
                    }
                });

                return isValid;
            }

            function NotifyWarning() {
                const messageWarning = "Vui lòng điền đầy đủ thông tin trước khi qua bước kế tiếp!";
                document.getElementById("content-warning").innerHTML = messageWarning;
                document.getElementById("content-warning").style.display = 'block';
            }

            function ClearWarning() {
                document.getElementById("content-warning").innerHTML = "";
                document.getElementById("content-warning").style.display = 'none';
            }

            nextBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (validateCurrentForm()) {
                        ClearWarning();
                        if (currentForm < formSections.length - 1) {
                            currentForm++;
                            showForm();

                        }
                    } else {
                        NotifyWarning();
                    }
                });
            });
            prevBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (currentForm > 0) {
                        currentForm--;
                        showForm();
                    }
                });
            });

            showForm();
        }

        initForm();
    });


    document.querySelector('.submit-btn').addEventListener('click', function() {
        var combinedForm = document.createElement('form');
        combinedForm.method = 'POST';
        combinedForm.action = '../client/process_cart.php';


        var forms = [document.getElementById('inforUser'), document.getElementById('inforProduct'), document.getElementById('inforShipping')];

        forms.forEach(function(form) {
            var inputs = form.querySelectorAll('input, select');
            inputs.forEach(function(input) {
                if (input.name && input.value) {
                    var hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.name = input.name;
                    hiddenField.value = input.value;
                    combinedForm.appendChild(hiddenField);
                }
            });
        });


        document.body.appendChild(combinedForm);
        combinedForm.submit();
    });



    document.getElementById("Select_product").onchange = function() {
        var productName = this.value;
        fetch("get_productname.php?product_name=" + productName)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);

                document.getElementById("price-display").innerText = data.price ? data.price + " VND" : "Không có giá";

                const showColor = document.getElementById('showColor');
                showColor.innerHTML = '<option value="" disabled selected hidden>Màu</option>';

                data.colors.forEach(color => {
                    const option = document.createElement('option');
                    option.value = color.color;
                    option.textContent = color.color;
                    option.disabled = !color.in_stock;
                    showColor.appendChild(option);
                });


                showColor.disabled = !data.inStock;

            })
            .catch(error => {
                console.error('Fetch error:', error);
                document.getElementById("price-display").innerText = "Lỗi khi nhận giá";
            });
    };



    document.getElementById('placeSelect').addEventListener('change', function() {
        const place = this.value;

        if (place) {
            fetch('get_showrooms.php?place=' + place)
                .then(response => response.json())
                .then(data => {
                    const showroomSelect = document.getElementById('showroomSelect');
                    showroomSelect.innerHTML = '<option value="" disabled selected hidden>Showroom nhận xe</option>';
                    data.forEach(showroom => {
                        const option = document.createElement('option');
                        option.value = showroom;
                        option.textContent = showroom;
                        showroomSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching showrooms:', error));
        }
    });
</script>

</html>