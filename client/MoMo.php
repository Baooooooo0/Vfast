<?php
session_start();
header('Content-type: text/html; charset=utf-8');

// KIỂM TRA ĐĂNG NHẬP VÀ DỮ LIỆU POST
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Nếu không có user_id hoặc không phải là POST request, chuyển hướng về trang sản phẩm
    header('Location: oto.php');
    exit;
}

// KẾT NỐI DATABASE
$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";
$data = new mysqli($host, $user, $password, $db);
if ($data->connect_error) {
    die("Connection failed: " . $data->connect_error);
}

// LẤY DỮ LIỆU TỪ FORM CỦA payment.php
$user_id = (int)$_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = (int)$_POST['quantity'];
$amount = (int)$_POST['amount']; // Số tiền thanh toán
$payment_type = $_POST['payment_type']; // 'deposit' hoặc 'full'

// Thông tin người nhận
$receiver_name = $_POST['receiver_name'] ?? '';
$receiver_phone = $_POST['receiver_phone'] ?? '';
$receiver_address = $_POST['receiver_address'] ?? '';

// TẠO MỘT GIAO DỊCH "ĐANG CHỜ" TRONG DATABASE
$orderId = 'MOMO' . time() . rand(1000, 9999); // Tạo mã đơn hàng duy nhất
$deposit_amount = ($payment_type === 'deposit') ? $amount : 0; // Nếu là đặt cọc thì lưu số tiền cọc

$sql = "INSERT INTO transactions (product_id, customer_id, transaction_date, deposit, transaction_number, payment_method, transaction_status, order_id, receiver_name, receiver_phone, receiver_address) VALUES (?, ?, NOW(), ?, ?, 'MOMO', 'pending', ?, ?, ?, ?)";

$stmt = $data->prepare($sql);
// Chú ý: transaction_number lưu số lượng sản phẩm
$stmt->bind_param("siisssss", $product_id, $user_id, $deposit_amount, $quantity, $orderId, $receiver_name, $receiver_phone, $receiver_address);

if (!$stmt->execute()) {
    // Nếu không thể tạo giao dịch, hiển thị lỗi
    die("Lỗi khi tạo giao dịch: " . $stmt->error);
}

// ĐÓNG KẾT NỐI DATABASE
$stmt->close();
$data->close();


//--- BẮT ĐẦU PHẦN XỬ LÝ API MOMO ---

// function helper
function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

$partnerCode = 'MOMOBKUN20180529';
$accessKey = 'klm05TvNBzhg7h7j';
$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
$orderInfo = "Thanh toán đơn hàng " . $orderId; 
// $amount đã được lấy từ POST ở trên
// $orderId đã được tạo ở trên
$redirectUrl = "http://vfast.vtest/client/oto.php"; // Trang sẽ chuyển đến sau khi thanh toán
$ipnUrl = "https://2fc1fe108424.ngrok-free.app/vfast/client/momo_ipn_handler.php"; // URL MoMo sẽ gửi kết quả về
$extraData = "";
    
$requestId = time() . "";
$requestType = "captureWallet";

$rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
$signature = hash_hmac("sha256", $rawHash, $secretKey);

$data_momo = array('partnerCode' => $partnerCode,
    'partnerName' => "Test",
    "storeId" => "MomoTestStore",
    'requestId' => $requestId,
    'amount' => $amount,
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl' => $ipnUrl,
    'lang' => 'vi',
    'extraData' => $extraData,
    'requestType' => $requestType,
    'signature' => $signature);

$result = execPostRequest($endpoint, json_encode($data_momo));
$jsonResult = json_decode($result, true); 

if (isset($jsonResult['payUrl'])) {
    header('Location: ' . $jsonResult['payUrl']);
    exit();
} else {
    echo "<h3>Có lỗi xảy ra với MoMo:</h3>";
    echo "<pre>";
    print_r($jsonResult);
    echo "</pre>";
}
?>