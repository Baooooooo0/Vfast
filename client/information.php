<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";

// Kết nối đến cơ sở dữ liệu
$data = new mysqli($host, $user, $password, $db);

// Kiểm tra kết nối
if ($data->connect_error) {
    die("Connection failed: " . $data->connect_error);
}

// Giả sử thông tin người dùng đã được lưu trong session khi đăng nhập
$user_id = $_SESSION['user_id'] ?? 1; // Sử dụng session hoặc giá trị mặc định là 1

// Truy vấn thông tin người dùng từ cơ sở dữ liệu
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $data->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra và lấy dữ liệu người dùng
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userName = $row['name'];
    $userEmail = $row['email'];

} else {
    echo "No user found";
    exit; // Thoát nếu không tìm thấy người dùng
}

$stmt->close();
$data->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <style>
    /* Đặt kiểu chữ và màu nền cho toàn bộ trang */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #e9ecef;
        margin: 0;
        padding: 0;
        color: #495057;
    }

    /* Container chính của trang */
    .container-fluid {
        width: 100%;
        max-width: 800px;
        margin: 160px auto;
        padding: 30px;
        background-color: #fff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    /* Container dạng lưới cho nội dung */
    .grid-container {
        display: flex;
        flex-direction: row;
        align-items: center;
    }

    /* Định dạng cho từng mục trong lưới */
    .grid-item {
        text-align: left;
        flex: 1;
    }

    /* Định dạng hình ảnh đại diện */
    .grid-item img {
        width: 160px;

        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #007bff;
        margin-right: 30px;
        transition: transform 0.3s ease-in-out;
    }

    .grid-item img:hover {
        transform: scale(1.05);
    }

    /* Định dạng cho các nhóm thông tin */
    .form-group {
        display: flex;
        justify-content: flex-start;
        margin-bottom: 20px;
        font-size: 18px;
    }

    .form-group b {
        width: 160px;
        text-align: left;
        color: #6c757d;
        margin-right: 20px;
    }

    .form-group span {
        flex: 1;
        text-align: left;
        color: #212529;
        font-weight: 600;
    }

    /* Định dạng cho tiêu đề */
    h1 {
        font-size: 28px;
        color: #007bff;
        margin-bottom: 20px;
text-align: center;
    }

    /* Responsive cho màn hình nhỏ hơn */
    @media (max-width: 600px) {
        .container-fluid {
            margin: 20px;
            padding: 20px;
        }

        .grid-container {
            flex-direction: column;
            align-items: center;
        }

        .grid-item {
            text-align: center;
            margin-bottom: 20px;
        }

        .grid-item img {
            margin-right: 0;
            margin-bottom: 20px;
        }

        .form-group {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .form-group b,
        .form-group span {
            width: 100%;
            padding: 5px 0;
        }
    }
    </style>
    
</head>

<body>
    <?php include('header.php'); ?>
    <div class="container-fluid">
        <div class="grid-container">
            <div class="grid-item">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ8czzbrLzXJ9R_uhKyMiwj1iGxKhJtH7pwlQ&s"
                    alt="Profile Picture" class="profile-img">
            </div>
            <div class="grid-item">
                <div class="form-group">
                    <b>Full Name:</b>
                    <span id="userName"><?php echo htmlspecialchars($userName); ?></span>
                </div>
                <div class="form-group">
                    <b>Email:</b>
                    <span id="userEmail"><?php echo htmlspecialchars($userEmail); ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>