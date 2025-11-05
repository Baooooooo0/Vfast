<?php 
// session_start();
//đã có session_start() trong home.php
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
        
        // Sử dụng INNER JOIN để lấy số lượng giao dịch dựa trên tên người dùng
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

        .notify {
            display: flex;
            align-items: center;
            justify-content: space-between; 
            gap: 25px; 
            min-width: 120px;
            position: relative;
        }


        .cart-count {
            position: absolute;
            top: -7px;
            left: 75px; /* Điều chỉnh vị trí sau khi xóa icon history */
            background: red;
            color: white;
            border-radius: 50%;
            width: 14px;
            height: 13px;
            font-size: 10px;
            text-align: center;
        }
        .count-heart{
            position: absolute;
            top: -7px;
            left: 10px;
            background: red;
            color: white;
            border-radius: 50%;
            width: 14px;
            height: 13px;
            font-size: 10px;
            text-align: center;
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
            <nav>
                <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <ul id="nav-links">
                    <li><a href="home.php">Giới thiệu</a></li>
                    <li><a href="oto.php">Ô tô</a></li>
                    <li><a href="service.php">Dịch vụ hậu mãi</a></li>
                </ul>
            </nav>
            <div class="box-car-head">
                <span class="account"><i class="fas fa-user"></i><p><?php echo htmlspecialchars($name); ?></p></span>
                <ul class="menu-account">
                    <li class="menu-account-item"><a href="../client/information.php">Thông tin cá nhân</a></li>
                    <li class="menu-account-item"><a href="logout.php">Đăng xuất</a></li>
                </ul>
            </div>
            <div class="notify">
            <div class="notify-item">
                <i class="fa fa-heart" id="like_car" aria-hidden="true" title="Xe đã thích"></i>
                <span class="count-heart"></span>
            </div>

            <div class="notify-item">
                <i class="fa fa-car" id="cart_car" aria-hidden="true" title="Lịch sử mua hàng"></i>
                <span class="cart-count"><?php echo $cart_count; ?></span>
            </div>
            </div>
        </div>
    </header>

    <script>
        document.getElementById('cart_car').addEventListener('click', function () {
            window.location.href = '../client/cart_car.php';
        });

        document.getElementById('like_car').onclick = function(){
            window.location.href = '../client/liked_car.php';
        }
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('nav-links');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('show');
        });
        function updateCountHeart() {
            const likeProducts = JSON.parse(localStorage.getItem('likeCars')) || [];
            const countHeart = likeProducts.length;
            document.querySelector('.count-heart').textContent =countHeart;
        }
        updateCountHeart();
    </script>
</body>

</html>