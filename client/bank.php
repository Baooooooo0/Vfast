<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Set email session if not exists for header compatibility
if (!isset($_SESSION['email']) && isset($_SESSION['user_id'])) {
    require_once realpath(__DIR__ . '/../config/db_connect.php');
    $sql_email = "SELECT email FROM users WHERE id = ?";
    $stmt_email = $conn->prepare($sql_email);
    $stmt_email->bind_param('i', $_SESSION['user_id']);
    $stmt_email->execute();
    $res_email = $stmt_email->get_result();
    if ($res_email->num_rows > 0) {
        $user_data = $res_email->fetch_assoc();
        $_SESSION['email'] = $user_data['email'];
    }
    $stmt_email->close();
}

require_once realpath(__DIR__ . '/../config/db_connect.php');

// ====== Cấu hình ======
$BANK_ACCOUNT_NUMBER = '0886219143';
$BANK_CODE = 'tpb'; // TPBank
$template = 'compact';

// ====== Dữ liệu thanh toán ======
$user_id = (int)$_SESSION['user_id'];
$product_id = trim($_POST['product_id'] ?? '');
$product_name = trim($_POST['product_name'] ?? '');
$product_price = (int)($_POST['product_price'] ?? 0);
$quantity = (int)($_POST['quantity'] ?? 1);
$payment_type = $_POST['payment_type'] ?? 'full';
$amount = (int)($_POST['amount'] ?? $product_price);
$order_id = 'ORD' . $product_id . 'U' . $user_id . time(); // ✅ khớp với webhook

// ====== Lấy thông tin giao hàng ======
$receiver_name = $receiver_phone = $receiver_address = null;
$sql_user = "SELECT receiver_name, receiver_phone, receiver_address FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param('i', $user_id);
$stmt_user->execute();
$res_user = $stmt_user->get_result();
if ($res_user->num_rows > 0) {
    $user = $res_user->fetch_assoc();
    $receiver_name = $user['receiver_name'];
    $receiver_phone = $user['receiver_phone'];
    $receiver_address = $user['receiver_address'];
}
$stmt_user->close();

// ====== Tạo đơn hàng PENDING ======
$sql = "
INSERT INTO transactions
(product_id, customer_id, transaction_date, deposit, transaction_number,
 payment_method, transaction_status, order_id, request_id,
 momo_trans_id, receiver_name, receiver_phone, receiver_address)
VALUES (?, ?, NOW(), ?, 0, 'Chuyển khoản ngân hàng', 'pending', ?, 1, NULL, ?, ?, ?)
";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    'siissss',
    $product_id,
    $user_id,
    $amount,
    $order_id,
    $receiver_name,
    $receiver_phone,
    $receiver_address
);
$stmt->execute();
$stmt->close();

// ====== Tạo QR SePay ======
$transfer_desc = $order_id;
$qr_url = 'https://qr.sepay.vn/img?' . http_build_query([
    'acc' => $BANK_ACCOUNT_NUMBER,
    'bank' => $BANK_CODE,
    'amount' => $amount,
    'des' => $transfer_desc,
    'template' => $template
]);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán chuyển khoản | VinFast</title>
    <?php include('home_css.php'); ?>
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 80px;
        }
        
        .payment-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 30px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }
        
        .payment-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .payment-header h1 {
            color: #1464F4;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .payment-header p {
            color: #666;
            font-size: 16px;
        }
        
        .payment-content {
            display: flex;
            gap: 40px;
            align-items: flex-start;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        
        .qr-section {
            flex: 1;
            min-width: 300px;
            text-align: center;
        }
        
        .qr-section img {
            max-width: 280px;
            width: 100%;
            height: auto;
            border-radius: 12px;
            border: 3px solid #1464F4;
            box-shadow: 0 4px 20px rgba(20, 100, 244, 0.2);
        }
        
        .qr-title {
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        
        .payment-info {
            flex: 1;
            min-width: 300px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .info-table th,
        .info-table td {
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-table th {
            text-align: left;
            width: 40%;
            color: #666;
            font-weight: 600;
        }
        
        .info-table td {
            color: #333;
            font-weight: 500;
        }
        
        .bank-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 25px;
            background: linear-gradient(135deg, #1464F4, #0052cc);
            color: white;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
        }
        
        .amount-highlight {
            color: #1464F4;
            font-size: 20px;
            font-weight: 700;
        }
        
        .transfer-code {
            background: #f8f9fa;
            padding: 12px 16px;
            border-radius: 8px;
            border-left: 4px solid #1464F4;
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #333;
            word-break: break-all;
        }
        
        .status-container {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            margin-top: 30px;
            border: 2px dashed #1464F4;
        }
        
        .status-box {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 600;
            color: #666;
        }
        
        .loading-spinner {
            width: 24px;
            height: 24px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #1464F4;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .success-status {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border-color: #28a745;
            color: #155724;
        }
        
        .invoice {
            margin-top: 30px;
            padding: 25px;
            border: 2px solid #28a745;
            border-radius: 12px;
            background: linear-gradient(135deg, #f8fff8, #f0fff0);
        }
        
        .invoice h3 {
            color: #28a745;
            margin-bottom: 20px;
            font-size: 24px;
        }
        
        .invoice-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .invoice-item {
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .invoice-item strong {
            color: #333;
        }
        
        .print-btn {
            padding: 12px 24px;
            background: linear-gradient(135deg, #1464F4, #0052cc);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(20, 100, 244, 0.3);
        }
        
        @media (max-width: 768px) {
            .payment-container {
                margin: 20px;
                padding: 20px;
            }
            
            .payment-content {
                flex-direction: column;
                gap: 30px;
            }
            
            .payment-header h1 {
                font-size: 24px;
            }
            
            .qr-section img {
                max-width: 250px;
            }
            
            .invoice-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    
    <div class="payment-container">
        <div class="payment-header">
            <h1><i class="fas fa-credit-card"></i> Thanh toán chuyển khoản ngân hàng</h1>
            <p>Quét mã QR hoặc chuyển khoản đúng nội dung để hệ thống tự động xác nhận thanh toán</p>
        </div>

        <div class="payment-content">
            <div class="qr-section">
                <div class="qr-title">
                    <i class="fas fa-qrcode"></i> Quét mã QR để thanh toán
                </div>
                <img src="<?= htmlspecialchars($qr_url) ?>" alt="QR chuyển khoản">
            </div>
            
            <div class="payment-info">
                <table class="info-table">
                    <tr>
                        <th><i class="fas fa-university"></i> Ngân hàng</th>
                        <td><span class="bank-badge"><?= strtoupper($BANK_CODE) ?> Bank</span></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-credit-card"></i> Số tài khoản</th>
                        <td><strong><?= htmlspecialchars($BANK_ACCOUNT_NUMBER) ?></strong></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-money-bill-wave"></i> Số tiền</th>
                        <td><span class="amount-highlight"><?= number_format($amount, 0, ',', '.') ?> VND</span></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-comment"></i> Nội dung chuyển khoản</th>
                        <td><div class="transfer-code"><?= htmlspecialchars($transfer_desc) ?></div></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-receipt"></i> Mã đơn hàng</th>
                        <td><?= htmlspecialchars($order_id) ?></td>
                    </tr>
                    <tr>
                        <th><i class="fas fa-car"></i> Sản phẩm</th>
                        <td><strong><?= htmlspecialchars($product_name) ?></strong></td>
                    </tr>
                </table>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Lưu ý:</strong> Vui lòng chuyển khoản đúng số tiền và nội dung để hệ thống tự động xác nhận.
                </div>
            </div>
        </div>

        <div class="status-container">
            <div id="statusBox" class="status-box">
                <div class="loading-spinner"></div>
                <span>Đang chờ thanh toán...</span>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

<script>
const orderId = "<?php echo $order_id ?? ''; ?>";

function pollPayment() {
  if (!orderId) return;
  fetch(`check_payment_status.php?order_id=${orderId}`)
    .then(res => res.json())
    .then(data => {
      console.log("Trạng thái thanh toán:", data);
      if (data.status === "completed" || data.payment_status === "Paid") {
        // Success notification
        const statusContainer = document.querySelector('.status-container');
        statusContainer.className = 'status-container success-status';
        
        // Create invoice HTML
        const invoiceHTML = `
          <div class="invoice">
            <h3><i class="fas fa-check-circle"></i> Thanh toán thành công!</h3>
            <div class="invoice-details">
              <div class="invoice-item">
                <strong>Mã đơn hàng:</strong> ${orderId}
              </div>
              <div class="invoice-item">
                <strong>Sản phẩm:</strong> <?= htmlspecialchars($product_name) ?>
              </div>
              <div class="invoice-item">
                <strong>Số lượng:</strong> <?= htmlspecialchars($quantity) ?>
              </div>
              <div class="invoice-item">
                <strong>Số tiền:</strong> <?= number_format($amount, 0, ',', '.') ?> VND
              </div>
              <div class="invoice-item">
                <strong>Ngày thanh toán:</strong> ${new Date().toLocaleString('vi-VN')}
              </div>
              <div class="invoice-item">
                <strong>Khách hàng:</strong> <?= htmlspecialchars($receiver_name) ?>
              </div>
              <div class="invoice-item">
                <strong>Số điện thoại:</strong> <?= htmlspecialchars($receiver_phone) ?>
              </div>
              <div class="invoice-item">
                <strong>Địa chỉ giao hàng:</strong> <?= htmlspecialchars($receiver_address) ?>
              </div>
            </div>
            <div style="text-align: center; margin-top: 20px;">
              <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print"></i> In hóa đơn
              </button>
              <button onclick="window.location.href='home.php'" class="print-btn" style="margin-left: 10px; background: linear-gradient(135deg, #28a745, #20c997);">
                <i class="fas fa-home"></i> Về trang chủ
              </button>
            </div>
          </div>
        `;

        // Update status box
        const box = document.getElementById('statusBox');
        box.innerHTML = `
          <div style="color: #28a745; font-size: 20px;">
            <i class="fas fa-check-circle"></i>
            <span>Thanh toán thành công!</span>
          </div>
          ${invoiceHTML}
        `;
      }
      else if (data.status === "pending" || data.status === "paid") {
        setTimeout(pollPayment, 4000);
      } else {
        console.log("⏳ Đang chờ thanh toán...");
        setTimeout(pollPayment, 4000);
      }
    })
    .catch(err => {
      console.error("Lỗi khi kiểm tra thanh toán:", err);
      setTimeout(pollPayment, 4000);
    });
}

// Start checking payment status after 5 seconds, repeat every 4 seconds
setTimeout(pollPayment, 5000);

// Add some interactive features
document.addEventListener('DOMContentLoaded', function() {
    // Copy transfer code on click
    const transferCode = document.querySelector('.transfer-code');
    if (transferCode) {
        transferCode.style.cursor = 'pointer';
        transferCode.title = 'Click để sao chép nội dung chuyển khoản';
        transferCode.addEventListener('click', function() {
            navigator.clipboard.writeText(this.textContent).then(function() {
                // Show success message
                const originalText = transferCode.textContent;
                transferCode.textContent = '✓ Đã sao chép!';
                transferCode.style.background = '#d4edda';
                transferCode.style.color = '#155724';
                
                setTimeout(function() {
                    transferCode.textContent = originalText;
                    transferCode.style.background = '#f8f9fa';
                    transferCode.style.color = '#333';
                }, 2000);
            });
        });
    }
    
    // Copy account number on click
    const accountNumber = document.querySelector('.info-table td strong');
    if (accountNumber) {
        accountNumber.style.cursor = 'pointer';
        accountNumber.title = 'Click để sao chép số tài khoản';
        accountNumber.addEventListener('click', function() {
            navigator.clipboard.writeText(this.textContent).then(function() {
                const originalText = accountNumber.textContent;
                accountNumber.textContent = '✓ Đã sao chép!';
                accountNumber.style.color = '#28a745';
                
                setTimeout(function() {
                    accountNumber.textContent = originalText;
                    accountNumber.style.color = '#333';
                }, 2000);
            });
        });
    }
});
</script>

</body>
</html>
