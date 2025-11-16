<?php
// File: header.php
// session_start();
//đã có session_start() trong home.php

// Xử lý reset flag
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'reset_liked_cars_flag') {
    $_SESSION['liked_cars_reset'] = false;
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";

if (defined('HEADER_INCLUDED')) return;
define('HEADER_INCLUDED', true);

$data = new mysqli($host, $user, $password, $db);

if (isset($_SESSION['email'])) {
    $email = $data->real_escape_string($_SESSION['email']);

    $sql = $data->prepare("SELECT name FROM users WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $result = $sql->get_result();

    $name = '';
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];

        // Sử dụng INNER JOIN để lấy số lượng giao dịch dựa trên user_id
        $cart_sql = $data->prepare("
            SELECT COUNT(*) as count 
            FROM transactions 
            INNER JOIN users ON transactions.customer_id = users.id 
            WHERE users.name = ?
        ");

        $cart_sql->bind_param("s", $name);
        $cart_sql->execute();
        $cart_result = $cart_sql->get_result();
        $cart_count_row = $cart_result->fetch_assoc();

        $_SESSION['cart_count'] = $cart_count_row['count'];
        $_SESSION['name'] = $name;
    } else {
        // Nếu không tìm thấy user, set cart_count = 0
        $_SESSION['cart_count'] = 0;
        $_SESSION['name'] = '';
    }
} else {

    header("Location: login.php");
    exit();
}

$cart_count = isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include('home_css.php'); ?>
    <style>
        .logo a {
            display: inline-block;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .logo a:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="navbar">
            <div class="logo">
                <a href="home.php">
                    <img alt="VinFast - Thương hiệu xe điện đầu tiên Việt Nam"
                        src="https://vinfastauto.com/themes/porto/img/new-home-page/VinFast-logo.svg">
                </a>
            </div>

            <button class="hamburger" id="hamburger" type="button">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav>
                <ul id="nav-links">
                    <li><a href="home.php">Giới thiệu</a></li>
                    <li><a href="oto.php">Ô tô</a></li>
                    <li><a href="service.php">Dịch vụ hậu mãi</a></li>
                    <li><a href="battery_station.php">Trạm Sạc VinFast</a></li>
                </ul>
            </nav>

            <div class="navbar-right">
                <div class="box-car-head">
                    <span class="account">
                        <i class="fas fa-user"></i>
                        <p><?php echo htmlspecialchars($name); ?></p>
                    </span>
                    <ul class="menu-account">
                        <li class="menu-account-item">
                            <a href="../client/information.php">Thông tin cá nhân</a>
                        </li>
                        <li class="menu-account-item">
                            <a href="logout.php">Đăng xuất</a>
                        </li>
                    </ul>
                </div>
                <div class="notify">
                    <div class="notify-item">
                        <i class="fa fa-heart" id="like_car" aria-hidden="true" title="Xe đã thích"></i>
                        <span class="count-heart"></span>
                    </div>
                    <div class="notify-item">
                        <i class="fa fa-car" id="cart_car" aria-hidden="true" title="Lịch sử mua hàng"></i>
                        <span class="cart-count" data-count="<?php echo $cart_count; ?>" style="<?php echo $cart_count > 0 ? 'display: flex;' : 'display: none;'; ?>"><?php echo $cart_count > 0 ? $cart_count : ''; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <script>
        // Xử lý click cho giỏ hàng
        document.getElementById('cart_car').addEventListener('click', function() {
            window.location.href = '../client/cart_car.php';
        });

        // Xử lý click cho xe đã thích
        document.getElementById('like_car').addEventListener('click', function() {
            window.location.href = '../client/liked_car.php';
        });

        // Hamburger menu
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('show');
        });

        // Cập nhật số lượng xe đã thích từ localStorage (theo user)
        function updateCountHeart() {
            const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '0'; ?>;
            const storageKey = 'likeCars_' + currentUserId;
            const likeProducts = JSON.parse(localStorage.getItem(storageKey)) || [];
            const countHeart = likeProducts.length;
            const heartCountElement = document.querySelector('.count-heart');

            if (heartCountElement) {
                if (countHeart > 0) {
                    heartCountElement.textContent = countHeart;
                    heartCountElement.style.display = 'flex';
                    heartCountElement.style.visibility = 'visible';
                } else {
                    heartCountElement.textContent = '';
                    heartCountElement.style.display = 'none';
                    heartCountElement.style.visibility = 'hidden';
                }
            }
        }

        // Làm function có thể access từ window
        window.updateCountHeart = updateCountHeart;

        // Function để clear localStorage cho user cũ khi đăng nhập user mới
        function clearPreviousUserData() {
            const shouldReset = <?php echo isset($_SESSION['liked_cars_reset']) && $_SESSION['liked_cars_reset'] ? 'true' : 'false'; ?>;
            if (shouldReset) {
                // Xóa tất cả localStorage keys liên quan đến likeCars của user khác
                for (let i = localStorage.length - 1; i >= 0; i--) {
                    const key = localStorage.key(i);
                    if (key && key.startsWith('likeCars_')) {
                        const userId = key.split('_')[1];
                        const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '0'; ?>;
                        if (userId != currentUserId) {
                            localStorage.removeItem(key);
                        }
                    }
                }
                // Reset flag
                fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=reset_liked_cars_flag'
                });
            }
        }

        // Cập nhật badge giỏ hàng
        function updateCartCount() {
            const cartCountElement = document.querySelector('.cart-count');
            const cartCount = <?php echo $cart_count; ?>;

            if (cartCountElement) {
                cartCountElement.setAttribute('data-count', cartCount);

                if (cartCount > 0) {
                    cartCountElement.textContent = cartCount;
                    cartCountElement.style.display = 'flex';
                    cartCountElement.style.visibility = 'visible';
                } else {
                    cartCountElement.textContent = '';
                    cartCountElement.style.display = 'none';
                    cartCountElement.style.visibility = 'hidden';
                }
            }
        }

        // Gọi function khi trang load
        document.addEventListener('DOMContentLoaded', function() {
            clearPreviousUserData();
            updateCountHeart();
            updateCartCount();
        });

        // Cập nhật khi có thay đổi trong localStorage
        window.addEventListener('storage', function(e) {
            const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '0'; ?>;
            const storageKey = 'likeCars_' + currentUserId;
            if (e.key === storageKey) {
                updateCountHeart();
            }
        });

        // Lắng nghe custom event từ các trang khác
        window.addEventListener('likeCountChanged', function(e) {
            updateCountHeart();
        });

        // Force update khi trang load xong
        setTimeout(updateCountHeart, 200);
    </script>
</body>

</html>