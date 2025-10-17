<?php
// momo_ipn_handler.php

// ===== BẮT ĐẦU ĐOẠN CODE DEBUG =====
// $log_file = __DIR__ . '/momo_log.txt';
// $log_message = "====== IPN Received at: " . date("Y-m-d H:i:s") . " ======\n";
// ===================================

// BƯỚC 1: ĐỌC DỮ LIỆU JSON THÔ TỪ MOMO
$raw_post_data = file_get_contents('php://input');//Dòng này có nghĩa là lấy toàn bộ dữ liệu thô được gửi đến từ MoMo bằng luồng php://input mà php cung cấp
$log_message .= "Raw JSON Body:\n" . $raw_post_data . "\n";//sau đó ghi lại dữ liệu thô này vào biến log_message để tiện theo dõi

// BƯỚC 2: GIẢI MÃ DỮ LIỆU JSON THÀNH MẢNG PHP
$momo_data = json_decode($raw_post_data, true);//giải mã dữ liệu JSON dạng text thành mảng PHP
$log_message .= "Decoded PHP Array:\n" . print_r($momo_data, true) . "\n";//nối thêm mảng PHP đã giải mã vào biến log_message để tiện theo dõi
file_put_contents($log_file, $log_message, FILE_APPEND);//ghi lại log bằng file_append để không bị ghi đè
// file_put_contents được dùng để ghi dữ liệu vào file. Ở đây, nó ghi nội dung của biến $log_message vào file được chỉ định bởi $log_file. Tham số FILE_APPEND đảm bảo rằng dữ liệu mới sẽ được thêm vào cuối file thay vì ghi đè lên nội dung cũ.
// KIỂM TRA NẾU DỮ LIỆU KHÔNG HỢP LỆ
if (empty($momo_data)) {
    // Ghi log lỗi và dừng lại
    file_put_contents($log_file, "ERROR: No data received or invalid JSON.\n", FILE_APPEND);
    exit();
}

// BƯỚC 3: SỬ DỤNG DỮ LIỆU TỪ MẢNG MỚI (thay vì $_POST)
$partnerCode = $momo_data['partnerCode'];
$orderId = $momo_data['orderId'];
$requestId = $momo_data['requestId'];
$amount = $momo_data['amount'];
$orderInfo = $momo_data['orderInfo'];
$orderType = $momo_data['orderType'];
$transId = $momo_data['transId'];
$resultCode = $momo_data['resultCode'];
$message = $momo_data['message'];
$payType = $momo_data['payType'];
$responseTime = $momo_data['responseTime'];
$extraData = $momo_data['extraData'];
$signature = $momo_data['signature'];

$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'; // Chuỗi bí mật

// TẠO CHỮ KÝ ĐỂ KIỂM TRA (Sử dụng dữ liệu từ $momo_data)
$rawHash = "accessKey=" . "klm05TvNBzhg7h7j" .
    "&amount=" . $amount .
    "&extraData=" . $extraData .
    "&message=" . $message .
    "&orderId=" . $orderId .
    "&orderInfo=" . $orderInfo .
    "&orderType=" . $orderType .
    "&partnerCode=" . $partnerCode .
    "&payType=" . $payType .
    "&requestId=" . $requestId .
    "&responseTime=" . $responseTime .
    "&resultCode=" . $resultCode .
    "&transId=" . $transId;

$partnerSignature = hash_hmac("sha256", $rawHash, $secretKey);

// GHI LẠI CHỮ KÝ ĐỂ SO SÁNH
$log_message_signature = "MoMo Signature: " . $signature . "\n";
$log_message_signature .= "Our Signature:  " . $partnerSignature . "\n";
if ($signature == $partnerSignature) {
    $log_message_signature .= ">>> SIGNATURE MATCHED! <<<\n";
} else {
    $log_message_signature .= ">>> SIGNATURE MISMATCHED! <<<\n";
}
file_put_contents($log_file, $log_message_signature, FILE_APPEND);


// KIỂM TRA CHỮ KÝ
if ($signature == $partnerSignature) {
    file_put_contents($log_file, "Signature check PASSED. ResultCode: " . $resultCode . "\n", FILE_APPEND);
    
    if ($resultCode == '0') {
        file_put_contents($log_file, "Payment SUCCESS. Connecting to DB...\n", FILE_APPEND);
        
        // KẾT NỐI DATABASE
        $host = "localhost";
        $user = "root";
        $password = "";
        $db = "carshop";
        $data = new mysqli($host, $user, $password, $db);
        if ($data->connect_error) {
            file_put_contents($log_file, "DB Connection FAILED: " . $data->connect_error . "\n", FILE_APPEND);
            die("Connection failed: " . $data->connect_error);
        }

        $data->begin_transaction();
        
        try {
            // Lấy thông tin giao dịch đang chờ
            $sql_select = "SELECT product_id, transaction_number FROM transactions WHERE order_id = ? AND transaction_status = 'pending' FOR UPDATE";
            $stmt_select = $data->prepare($sql_select);
            $stmt_select->bind_param("s", $orderId);
            $stmt_select->execute();
            $result = $stmt_select->get_result();
            $transaction = $result->fetch_assoc();
            
            file_put_contents($log_file, "Searching for transaction with orderId: " . $orderId . "\nTransaction found: " . print_r($transaction, true) . "\n", FILE_APPEND);

            if ($transaction) {
                $product_id = $transaction['product_id'];
                $quantity = (int)$transaction['transaction_number'];

                // Cập nhật trạng thái giao dịch
                $sql_update_trans = "UPDATE transactions SET transaction_status = 'completed', momo_trans_id = ? WHERE order_id = ?";
                $stmt_update_trans = $data->prepare($sql_update_trans);
                $stmt_update_trans->bind_param("ss", $transId, $orderId);
                $stmt_update_trans->execute();
                file_put_contents($log_file, "Updated transactions status to 'completed'.\n", FILE_APPEND);

                // Trừ số lượng sản phẩm
                $sql_update_product = "UPDATE product SET product_number = product_number - ? WHERE product_id = ?";
                $stmt_update_product = $data->prepare($sql_update_product);
                $stmt_update_product->bind_param("is", $quantity, $product_id);
                $stmt_update_product->execute();
                file_put_contents($log_file, "Decremented product stock.\n", FILE_APPEND);
                
                $data->commit();
                file_put_contents($log_file, "DB transaction COMMITTED.\n", FILE_APPEND);
            } else {
                $data->rollback();
                file_put_contents($log_file, "No pending transaction found for this orderId. Rolled back.\n", FILE_APPEND);
            }

        } catch (Exception $e) {
            $data->rollback();
            file_put_contents($log_file, "An exception occurred: " . $e->getMessage() . "\nDB transaction ROLLED BACK.\n", FILE_APPEND);
        }
        
        $data->close();
    } else {
        // Cập nhật trạng thái 'failed' nếu thanh toán thất bại
        file_put_contents($log_file, "Payment FAILED with resultCode: " . $resultCode . "\n", FILE_APPEND);
        // ... (code cập nhật trạng thái failed)
    }
} else {
    // Chữ ký không hợp lệ
    file_put_contents($log_file, "Signature check FAILED.\n", FILE_APPEND);
}
?>