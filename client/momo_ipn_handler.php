<?php
// momo_ipn_handler.php

// ===== BẮT ĐẦU ĐOẠN CODE DEBUG =====
$log_file = __DIR__ . '/momo_log.txt';
$log_message = "====== IPN Received at: " . date("Y-m-d H:i:s") . " ======\n";
// ===================================

// BƯỚC 1: ĐỌC DỮ LIỆU JSON THÔ TỪ MOMO
$raw_post_data = file_get_contents('php://input');
$log_message .= "Raw JSON Body:\n" . $raw_post_data . "\n";

// BƯỚC 2: GIẢI MÃ JSON
$momo_data = json_decode($raw_post_data, true);
$log_message .= "Decoded PHP Array:\n" . print_r($momo_data, true) . "\n";
file_put_contents($log_file, $log_message, FILE_APPEND);

if (empty($momo_data)) {
    file_put_contents($log_file, "ERROR: No data received or invalid JSON.\n", FILE_APPEND);
    exit();
}

// BƯỚC 3: LẤY DỮ LIỆU TỪ MOMO
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

$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

// CHUẨN HÓA RAW HASH
$rawHash = 
    "accessKey=klm05TvNBzhg7h7j" .
    "&amount=$amount" .
    "&extraData=$extraData" .
    "&message=$message" .
    "&orderId=$orderId" .
    "&orderInfo=$orderInfo" .
    "&orderType=$orderType" .
    "&partnerCode=$partnerCode" .
    "&payType=$payType" .
    "&requestId=$requestId" .
    "&responseTime=$responseTime" .
    "&resultCode=$resultCode".
    "&transId=$transId";

$partnerSignature = hash_hmac("sha256", $rawHash, $secretKey);

// GHI LOG SO SÁNH CHỮ KÝ
$log_sig = "MoMo Signature: $signature\nOur Signature:  $partnerSignature\n";
$log_sig .= ($signature == $partnerSignature)
            ? ">>> SIGNATURE MATCHED! <<<\n"
            : ">>> SIGNATURE MISMATCHED! <<<\n";
file_put_contents($log_file, $log_sig, FILE_APPEND);

// ==========================
// BẮT ĐẦU XỬ LÝ THANH TOÁN
// ==========================

if ($signature == $partnerSignature) {
    file_put_contents($log_file, "Signature check PASSED. ResultCode: $resultCode\n", FILE_APPEND);

    if ($resultCode == '0') {
        file_put_contents($log_file, "Payment SUCCESS. Connecting to DB...\n", FILE_APPEND);

        // ====== KẾT NỐI DATABASE ======
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
            // TÌM GIAO DỊCH
$sql_select = "SELECT product_id, transaction_number FROM transactions WHERE order_id = ? AND transaction_status = 'pending' FOR UPDATE";
            $stmt_select = $data->prepare($sql_select);
            $stmt_select->bind_param("s", $orderId);
            $stmt_select->execute();
            $result = $stmt_select->get_result();
            $transaction = $result->fetch_assoc();

            file_put_contents($log_file,
                "Searching for transaction with orderId: $orderId\nTransaction found: "
                . print_r($transaction, true) . "\n",
                FILE_APPEND
            );

            if ($transaction) {
                $product_id = $transaction['product_id'];
                $quantity = (int)$transaction['transaction_number'];

                // CẬP NHẬT TRẠNG THÁI
                $sql_update_trans = "UPDATE transactions SET transaction_status = 'completed', momo_trans_id = ? WHERE order_id = ?";
                $stmt_update_trans = $data->prepare($sql_update_trans);
                $stmt_update_trans->bind_param("ss", $transId, $orderId);
                $stmt_update_trans->execute();
                file_put_contents($log_file, "Updated transaction status to 'completed'.\n", FILE_APPEND);

                // TRỪ KHO
                $sql_update_product = "UPDATE product SET product_number = product_number - ? WHERE product_id = ?";
                $stmt_update_product = $data->prepare($sql_update_product);
                $stmt_update_product->bind_param("is", $quantity, $product_id);
                $stmt_update_product->execute();
                file_put_contents($log_file, "Updated product stock (−$quantity).\n", FILE_APPEND);

                // ================================
                // 🔥 TÍCH HỢP GỬI HÓA ĐƠN EMAIL 🔥
                // ================================
                include_once __DIR__ . '/send_invoice.php';

                if (function_exists('send_invoice_for_order')) {
                    $sent = send_invoice_for_order($orderId, $data);

                    if ($sent) {
                        file_put_contents($log_file,
                            "📧 Invoice sent SUCCESS for orderId $orderId\n",
                            FILE_APPEND
                        );
                    } else {
                        file_put_contents($log_file,
                            "❌ Invoice sending FAILED for orderId $orderId\n",
                            FILE_APPEND
                        );
                    }
                } else {
                    file_put_contents($log_file,
                        "⚠️ FUNCTION send_invoice_for_order() NOT FOUND!\n",
                        FILE_APPEND
                    );
                }

                // HOÀN TẤT
                $data->commit();
                file_put_contents($log_file, "DB transaction COMMITTED.\n", FILE_APPEND);

            } else {
                $data->rollback();
file_put_contents($log_file,
                    "No pending transaction found. DB ROLLED BACK.\n",
                    FILE_APPEND
                );
            }

        } catch (Exception $e) {
            $data->rollback();
            file_put_contents($log_file,
                "EXCEPTION: " . $e->getMessage() . "\nROLLED BACK.\n",
                FILE_APPEND
            );
        }

        $data->close();
    } else {
        file_put_contents($log_file, "Payment FAILED: resultCode = $resultCode\n", FILE_APPEND);
    }
} else {
    file_put_contents($log_file, "Signature check FAILED.\n", FILE_APPEND);
}
?>