<?php
// Bật báo lỗi để debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
require_once __DIR__ . '/../config/db_connect.php';

// Lấy dữ liệu từ request
$input = json_decode(file_get_contents('php://input'), true);
$userMessage = trim($input['message'] ?? '');

// Log để debug
error_log("ChatBot Request: " . print_r($input, true));

if (!$userMessage) {
    echo json_encode([
        "success" => true, 
        "response" => "Anh/chị đang tìm xe theo tiêu chí nào ạ? 😊"
    ]);
    exit;
}

// Xử lý yêu cầu "Xem thêm xe"
if (strtolower($userMessage) === 'xem thêm xe') {
    echo json_encode([
        "success" => true, 
        "response" => "🔍 <strong>Để xem thêm xe:</strong><br>
        • Nhập lại yêu cầu với từ khóa <strong>'tất cả'</strong><br>
        • Ví dụ: 'Tất cả xe màu đỏ dưới 500 triệu'<br>
        • Hoặc: 'Hiển thị tất cả xe 5 chỗ'<br><br>
        💡 Tôi sẽ hiển thị toàn bộ kết quả phù hợp!"
    ]);
    exit;
}

// Đọc API Key Gemini từ file .env
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, 'GEMINI_API_KEY') === 0) {
            $parts = explode('=', $line, 2);
            $GEMINI_API_KEY = trim(str_replace('"', '', $parts[1]));
            break;
        }
    }
}

if (empty($GEMINI_API_KEY)) {
    echo json_encode([
        "success" => false,
        "response" => "Lỗi: Không tìm thấy API key Gemini. Vui lòng kiểm tra file .env"
    ]);
    exit;
}

try {
    // Thử sử dụng AI trước
    $analysis = null;
    
    if (!empty($GEMINI_API_KEY) && $GEMINI_API_KEY !== 'YOUR_GEMINI_API_KEY_HERE') {
        try {
            $analysisPrompt = "
Phân tích câu hỏi của khách hàng về xe VinFast và trả về JSON với định dạng:
{
    \"intent\": \"search_car|product_info|compare|general\",
    \"price_min\": number|null,
    \"price_max\": number|null,
    \"color\": \"string|null\",
    \"product_name\": \"string|null\",
    \"seat_count\": number|null,
    \"features\": [\"string array|null\"]
}

QUY TẮC PHÂN TÍCH:
- \"dưới X triệu/tỷ\" → price_max = X * 1000000 (triệu) hoặc X * 1000000000 (tỷ)
- \"trên X triệu/tỷ\" → price_min = X * 1000000 (triệu) hoặc X * 1000000000 (tỷ)  
- \"khoảng X triệu\" → price_min = (X-100)*1000000, price_max = (X+100)*1000000
- Màu sắc: trắng, đen, đỏ, xanh, vàng, xanh lục, vàng hoàng hôn
- Tên xe: VF3, VF5, VF6, VFe34, VF7, VF8, VF9
- Intent: search_car (tìm xe), product_info (thông số), compare (so sánh), general (chung chung)

Câu hỏi: \"$userMessage\"
Chỉ trả về JSON, không giải thích.
";

            $response = callGeminiAPI($GEMINI_API_KEY, $analysisPrompt);
            $analysis = json_decode($response, true);
        } catch (Exception $e) {
            // Fallback: Phân tích bằng regex nếu AI không hoạt động
            $analysis = analyzeWithoutAI($userMessage);
        }
    } else {
        // Fallback: Không có API key
        $analysis = analyzeWithoutAI($userMessage);
    }

    if (!$analysis) {
        $analysis = analyzeWithoutAI($userMessage);
    }

    $intent = $analysis['intent'] ?? 'general';
    $result = "";

    switch ($intent) {
        case 'search_car':
            $result = searchCars($conn, $analysis, $userMessage);
            break;
        case 'product_info':
            $result = getProductInfo($conn, $analysis);
            break;
        case 'compare':
            $result = compareCars($conn, $analysis);
            break;
        default:
            $result = handleGeneralQuestion($conn, $userMessage);
            break;
    }

    echo json_encode([
        "success" => true,
        "response" => $result
    ]);

} catch (Exception $e) {
    error_log("ChatBot Error: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "response" => "Xin lỗi, đã có lỗi xảy ra: " . $e->getMessage() . " (Debug: Line " . $e->getLine() . ")"
    ]);
}

// Hàm gọi Gemini API với cURL
function callGeminiAPI($apiKey, $prompt) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=" . $apiKey;
    
    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $prompt]
                ]
            ]
        ]
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($response === false || !empty($error)) {
        throw new Exception("Lỗi kết nối cURL: " . $error);
    }
    
    if ($httpCode !== 200) {
        throw new Exception("Gemini API trả về mã lỗi: " . $httpCode);
    }

    $responseData = json_decode($response, true);
    
    if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
        return $responseData['candidates'][0]['content']['parts'][0]['text'];
    }
    
    throw new Exception("Phản hồi không hợp lệ từ Gemini API: " . json_encode($responseData));
}

// Hàm phân tích không cần AI (fallback)
function analyzeWithoutAI($message) {
    $lowerMessage = strtolower($message);
    $analysis = [
        'intent' => 'general',
        'price_min' => null,
        'price_max' => null,
        'color' => null,
        'product_name' => null,
        'seat_count' => null
    ];
    
    // Phân tích giá
    if (preg_match('/dưới\s*(\d+)\s*triệu/i', $message, $matches)) {
        $analysis['price_max'] = intval($matches[1]) * 1000000;
        $analysis['intent'] = 'search_car';
    }
    if (preg_match('/trên\s*(\d+)\s*triệu/i', $message, $matches)) {
        $analysis['price_min'] = intval($matches[1]) * 1000000;
        $analysis['intent'] = 'search_car';
    }
    if (preg_match('/khoảng\s*(\d+)\s*triệu/i', $message, $matches)) {
        $price = intval($matches[1]) * 1000000;
        $analysis['price_min'] = $price - 100000000;
        $analysis['price_max'] = $price + 100000000;
        $analysis['intent'] = 'search_car';
    }
    
    // Phân tích màu sắc
    $colors = ['trắng', 'đen', 'đỏ', 'xanh', 'vàng', 'xanh lục'];
    foreach ($colors as $color) {
        if (strpos($lowerMessage, $color) !== false) {
            $analysis['color'] = $color;
            $analysis['intent'] = 'search_car';
            break;
        }
    }
    
    // Phân tích tên xe
    $carNames = ['vf3', 'vf5', 'vf6', 'vfe34', 'vf7', 'vf8', 'vf9'];
    foreach ($carNames as $carName) {
        if (strpos($lowerMessage, $carName) !== false) {
            $analysis['product_name'] = strtoupper($carName);
            $analysis['intent'] = 'product_info';
            break;
        }
    }
    
    // Phân tích số ghế
    if (preg_match('/(\d+)\s*chỗ/i', $message, $matches)) {
        $analysis['seat_count'] = intval($matches[1]);
        $analysis['intent'] = 'search_car';
    }
    
    // Phân tích intent khác
    if (strpos($lowerMessage, 'so sánh') !== false || strpos($lowerMessage, 'compare') !== false) {
        $analysis['intent'] = 'compare';
    }
    
    return $analysis;
}

// Hàm tìm kiếm xe
function searchCars($conn, $analysis, $userMessage = '') {
    $priceMin = $analysis['price_min'];
    $priceMax = $analysis['price_max'];
    $color = $analysis['color'];
    $seatCount = $analysis['seat_count'];

    // Kiểm tra xem có yêu cầu hiển thị tất cả không
    $showAll = (strpos(strtolower($userMessage), 'tất cả') !== false);
    $limit = $showAll ? 50 : 5; // Giới hạn 50 kết quả nếu yêu cầu tất cả

    // Xây dựng query với ưu tiên màu sắc trước
    // Kiểm tra xem có yêu cầu hiển thị tất cả không
    $showAll = (strpos(strtolower($userMessage), 'tất cả') !== false);
    $limit = $showAll ? 50 : 5; // Giới hạn 50 kết quả nếu yêu cầu tất cả

    // Debug log
    error_log("SearchCars - Color: $color, PriceMax: $priceMax, PriceMin: $priceMin, ShowAll: " . ($showAll ? 'true' : 'false'));

    $sql = "SELECT * FROM product WHERE status = 'còn'";
    $params = [];
    $types = "";

    if ($color) {
        $sql .= " AND color = ?";
        $params[] = $color;
        $types .= "s";
    }

    if ($priceMin) {
        $sql .= " AND product_price >= ?";
        $params[] = $priceMin;
        $types .= "i";
    }

    if ($priceMax) {
        $sql .= " AND product_price <= ?";
        $params[] = $priceMax;
        $types .= "i";
    }

    if ($seatCount) {
        $sql .= " AND seat_count = ?";
        $params[] = $seatCount;
        $types .= "i";
    }

    $sql .= " ORDER BY product_price ASC LIMIT $limit";

    error_log("Final SQL: $sql");
    error_log("Params: " . print_r($params, true));

    try {
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $cars = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // Đếm tổng số kết quả để biết có còn xe khác không
        $countSql = str_replace("SELECT *", "SELECT COUNT(*)", $sql);
        $countSql = str_replace(" ORDER BY product_price ASC LIMIT $limit", "", $countSql);
        
        $countStmt = $conn->prepare($countSql);
        if (!empty($params)) {
            $countStmt->bind_param($types, ...$params);
        }
        $countStmt->execute();
        $totalCount = $countStmt->get_result()->fetch_row()[0];

        error_log("Found " . count($cars) . " cars out of $totalCount total");
    } catch (Exception $e) {
        error_log("Database error: " . $e->getMessage());
        throw new Exception("Lỗi truy vấn cơ sở dữ liệu: " . $e->getMessage());
    }

    // Nếu không tìm thấy xe với màu yêu cầu, tìm xe khác trong tầm giá
    if (empty($cars) && $color) {
        error_log("No cars found with color $color, searching alternatives...");
        
        $sqlAlternative = "SELECT * FROM product WHERE status = 'còn'";
        $altParams = [];
        $altTypes = "";

        if ($priceMin) {
            $sqlAlternative .= " AND product_price >= ?";
            $altParams[] = $priceMin;
            $altTypes .= "i";
        }

        if ($priceMax) {
            $sqlAlternative .= " AND product_price <= ?";
            $altParams[] = $priceMax;
            $altTypes .= "i";
        }

        if ($seatCount) {
            $sqlAlternative .= " AND seat_count = ?";
            $altParams[] = $seatCount;
            $altTypes .= "i";
        }

        $sqlAlternative .= " ORDER BY product_price ASC LIMIT " . ($showAll ? 50 : 5);

        try {
            $stmtAlt = $conn->prepare($sqlAlternative);
            if (!empty($altParams)) {
                $stmtAlt->bind_param($altTypes, ...$altParams);
            }
            $stmtAlt->execute();
            $alternativeCars = $stmtAlt->get_result()->fetch_all(MYSQLI_ASSOC);

            // Đếm tổng xe thay thế
            $altCountSql = str_replace("SELECT *", "SELECT COUNT(*)", $sqlAlternative);
            $altCountSql = str_replace(" ORDER BY product_price ASC LIMIT " . ($showAll ? 50 : 5), "", $altCountSql);
            
            $altCountStmt = $conn->prepare($altCountSql);
            if (!empty($altParams)) {
                $altCountStmt->bind_param($altTypes, ...$altParams);
            }
            $altCountStmt->execute();
            $altTotalCount = $altCountStmt->get_result()->fetch_row()[0];

            if (!empty($alternativeCars)) {
                return formatSearchResponse($cars, $alternativeCars, $color, $priceMax, $totalCount, $altTotalCount, $showAll);
            }
        } catch (Exception $e) {
            error_log("Error in alternative search: " . $e->getMessage());
        }
    }

    return formatSearchResponse($cars, [], $color, $priceMax, $totalCount, 0, $showAll);
}

// Hàm định dạng kết quả tìm kiếm
function formatSearchResponse($cars, $alternativeCars, $requestedColor, $maxPrice, $totalCount = 0, $altTotalCount = 0, $showAll = false) {
    $response = "";

    if (!empty($cars)) {
        if ($showAll) {
            $response .= "🚗 <strong>Tất cả $totalCount xe phù hợp:</strong><br><br>";
        } else {
            $response .= "🚗 <strong>Tìm thấy $totalCount xe phù hợp (hiển thị 5 xe đầu):</strong><br><br>";
        }
        
        foreach ($cars as $car) {
            $response .= formatCarCard($car);
        }
        
        if (!$showAll && $totalCount > 5) {
            $remaining = $totalCount - 5;
            $response .= "<br><div style='text-align: center; margin: 15px 0;'>";
            $response .= "<button onclick='showMoreCars()' style='background: linear-gradient(45deg, #1464F4, #0040FF); color: white; border: none; padding: 10px 20px; border-radius: 20px; cursor: pointer; font-size: 14px;'>";
            $response .= "🔍 Xem thêm $remaining xe khác</button>";
            $response .= "</div>";
        }
        
    } else if (!empty($alternativeCars)) {
        $response .= "😊 Rất tiếc không tìm thấy xe màu <strong>$requestedColor</strong>";
        if ($maxPrice) {
            $response .= " trong tầm giá dưới " . number_format($maxPrice / 1000000, 0) . " triệu";
        }
        $response .= ".<br><br>";
        
        if ($showAll) {
            $response .= "🎯 <strong>Tất cả $altTotalCount xe khác trong tầm giá:</strong><br><br>";
        } else {
            $response .= "🎯 <strong>Tuy nhiên, tôi tìm thấy $altTotalCount xe khác trong tầm giá (hiển thị 5 xe đầu):</strong><br><br>";
        }
        
        foreach ($alternativeCars as $car) {
            $response .= formatCarCard($car);
        }
        
        if (!$showAll && $altTotalCount > 5) {
            $remaining = $altTotalCount - 5;
            $response .= "<br><div style='text-align: center; margin: 15px 0;'>";
            $response .= "<button onclick='showMoreCars()' style='background: linear-gradient(45deg, #1464F4, #0040FF); color: white; border: none; padding: 10px 20px; border-radius: 20px; cursor: pointer; font-size: 14px;'>";
            $response .= "🔍 Xem thêm $remaining xe khác</button>";
            $response .= "</div>";
        }
        
    } else {
        $response = "😔 Rất tiếc, hiện tại không có xe nào phù hợp với tiêu chí của bạn. Bạn có thể thử:<br>";
        $response .= "• Tăng ngân sách<br>";
        $response .= "• Chọn màu sắc khác<br>";
        $response .= "• Liên hệ để được tư vấn thêm: <strong>1900-23-23-89</strong>";
    }

    return $response;
}

// Hàm định dạng thẻ xe
function formatCarCard($car) {
    $price = number_format($car['product_price'], 0, ',', '.');
    $detailLink = "detail.php?product_id={$car['product_id']}";
    
    return "
    <div class='car-card' onclick='window.open(\"$detailLink\", \"_blank\")' style='cursor: pointer;'>
        <div class='car-name'>🚘 {$car['product_name']} - Màu {$car['color']}</div>
        <div class='car-details'>
            📏 <strong>Kích thước:</strong> {$car['dimensions']}<br>
            🔋 <strong>Pin:</strong> {$car['battery_capacity']} kWh<br>
            🛞 <strong>Lazang:</strong> {$car['wheel_type']}<br>
            👥 <strong>Số ghế:</strong> {$car['seat_count']} chỗ<br>
            🛡️ <strong>Túi khí:</strong> {$car['airbags']} túi<br>
            📦 <strong>Còn lại:</strong> {$car['product_number']} chiếc
        </div>
        <div class='car-price'>💰 {$price} VNĐ</div>
        <div style='text-align: center; margin-top: 10px; font-size: 12px; color: #666;'>
            👆 Click để xem chi tiết
        </div>
    </div>";
}

// Hàm lấy thông tin sản phẩm
function getProductInfo($conn, $analysis) {
    $productName = $analysis['product_name'];
    
    if (!$productName) {
        return "❓ Bạn muốn xem thông số của dòng xe nào? (VF3, VF5, VF6, VFe34, VF7, VF8, VF9)";
    }

    $sql = "SELECT * FROM product WHERE product_name LIKE ? AND status = 'còn' ORDER BY color";
    $searchTerm = "%$productName%";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $cars = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (empty($cars)) {
        return "😔 Không tìm thấy thông tin về dòng xe <strong>$productName</strong>.";
    }

    $response = "📋 <strong>Thông số kỹ thuật {$cars[0]['product_name']}:</strong><br><br>";
    $response .= "📐 <strong>Kích thước:</strong> {$cars[0]['dimensions']}<br>";
    $response .= "🔋 <strong>Dung lượng pin:</strong> {$cars[0]['battery_capacity']} kWh<br>";
    $response .= "🛞 <strong>Lazang:</strong> {$cars[0]['wheel_type']}<br>";
    $response .= "👥 <strong>Số ghế:</strong> {$cars[0]['seat_count']} chỗ<br>";
    $response .= "🛡️ <strong>Túi khí an toàn:</strong> {$cars[0]['airbags']} túi<br><br>";

    $response .= "🎨 <strong>Các màu có sẵn:</strong><br>";
    foreach ($cars as $car) {
        $price = number_format($car['product_price'], 0, ',', '.');
        $response .= "• Màu <strong>{$car['color']}</strong>: {$price} VNĐ (còn {$car['product_number']} chiếc)<br>";
    }

    return $response;
}

// Hàm so sánh xe
function compareCars($conn, $analysis) {
    // Logic so sánh đơn giản - có thể mở rộng thêm
    $sql = "SELECT DISTINCT product_name FROM product WHERE status = 'còn' ORDER BY product_name";
    $result = $conn->query($sql);
    $cars = $result->fetch_all(MYSQLI_ASSOC);

    $response = "🔍 <strong>So sánh các dòng xe VinFast:</strong><br><br>";
    
    foreach ($cars as $car) {
        $detailSql = "SELECT * FROM product WHERE product_name = ? AND status = 'còn' ORDER BY product_price LIMIT 1";
        $stmt = $conn->prepare($detailSql);
        $stmt->bind_param("s", $car['product_name']);
        $stmt->execute();
        $detail = $stmt->get_result()->fetch_assoc();
        
        if ($detail) {
            $price = number_format($detail['product_price'], 0, ',', '.');
            $response .= "🚗 <strong>{$detail['product_name']}</strong><br>";
            $response .= "   💰 Từ: {$price} VNĐ<br>";
            $response .= "   👥 {$detail['seat_count']} chỗ | 🔋 {$detail['battery_capacity']} kWh<br><br>";
        }
    }

    $response .= "💡 Bạn muốn so sánh chi tiết 2 dòng xe nào? Hãy hỏi tôi!";
    
    return $response;
}

// Hàm xử lý câu hỏi chung
function handleGeneralQuestion($conn, $message) {
    // Trả lời các câu hỏi chung về VinFast
    $lowerMessage = strtolower($message);
    
    if (strpos($lowerMessage, 'giá') !== false) {
        return "💰 <strong>Bảng giá xe VinFast hiện tại:</strong><br>
        🚗 VF3: Từ 240-322 triệu VNĐ<br>
        🚗 VF5: Từ 538 triệu VNĐ<br>
        🚗 VF6: Từ 675 triệu VNĐ<br>
        🚗 VFe34: Từ 690 triệu VNĐ<br>
        🚗 VF7: Từ 850 triệu VNĐ<br>
        🚗 VF8: Từ 1.1 tỷ VNĐ<br>
        🚗 VF9: Từ 1.5 tỷ VNĐ<br><br>
        📞 Liên hệ: 1900-23-23-89 để biết thêm ưu đãi!";
    }
    
    if (strpos($lowerMessage, 'bảo hành') !== false || strpos($lowerMessage, 'warranty') !== false) {
        return "🛡️ <strong>Chính sách bảo hành VinFast:</strong><br>
        • Bảo hành xe: 10 năm hoặc 200,000km<br>
        • Bảo hành pin: 10 năm<br>
        • Dịch vụ cứu hộ 24/7<br>
        • Bảo dưỡng định kỳ miễn phí<br>
        📞 Hotline: 1900-23-23-89";
    }
    
    if (strpos($lowerMessage, 'showroom') !== false || strpos($lowerMessage, 'địa chỉ') !== false) {
        return "📍 <strong>Hệ thống showroom VinFast:</strong><br>
        🏢 Hà Nội: 17 showroom<br>
        🏢 TP.HCM: 12 showroom<br>
        🏢 Đà Nẵng: 3 showroom<br>
        🏢 Các tỉnh thành khác: 50+ showroom<br><br>
        🔍 Tìm showroom gần nhất: vinfast.vn/showroom<br>
        📞 Hotline: 1900-23-23-89";
    }
    
    return "🤖 Tôi có thể giúp bạn:<br>
    • Tìm xe theo màu sắc và giá tiền<br>
    • Xem thông số kỹ thuật các dòng xe<br>
    • So sánh các dòng xe<br>
    • Tư vấn về giá cả, bảo hành<br><br>
    💬 Hãy hỏi tôi bất cứ điều gì về xe VinFast!";
}
?>