<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Xử lý preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Kết nối database
include('../config/db_connect.php');

try {
    // Lấy parameters từ request
    $action = $_GET['action'] ?? '';
    $query = $_GET['query'] ?? '';
    $type = $_GET['type'] ?? '';
    $maxDistance = $_GET['maxDistance'] ?? '';
    $amenity = $_GET['amenity'] ?? '';
    $userLat = $_GET['lat'] ?? '';
    $userLng = $_GET['lng'] ?? '';
    $limit = intval($_GET['limit'] ?? 20);
    $offset = intval($_GET['offset'] ?? 0);

    switch ($action) {
        case 'search':
            searchStations($conn, $query, $type, $maxDistance, $amenity, $userLat, $userLng, $limit, $offset);
            break;
        case 'nearby':
            getNearbyStations($conn, $userLat, $userLng, $limit, $offset);
            break;
        case 'all':
            getAllStations($conn, $limit, $offset);
            break;
        case 'detail':
            $stationId = $_GET['id'] ?? '';
            getStationDetail($conn, $stationId);
            break;
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

function searchStations($conn, $query, $type, $maxDistance, $amenity, $userLat, $userLng, $limit, $offset) {
    $sql = "SELECT 
                station_id, 
                station_name, 
                address, 
                latitude, 
                longitude, 
                station_type, 
                power_capacity, 
                total_ports, 
                available_ports, 
                amenities, 
                status, 
                operating_hours, 
                pricing";
    
    // Tính khoảng cách nếu có tọa độ người dùng
    if ($userLat && $userLng) {
        $sql .= ", (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distance";
    } else {
        $sql .= ", 0 as distance";
    }
    
    $sql .= " FROM charging_stations WHERE 1=1";
    
    $params = [];
    $types = "";
    
    // Thêm tọa độ cho tính khoảng cách
    if ($userLat && $userLng) {
        $params[] = $userLat;
        $params[] = $userLng;
        $params[] = $userLat;
        $types .= "ddd";
    }
    
    // Tìm kiếm theo tên hoặc địa chỉ
    if ($query) {
        $sql .= " AND (station_name LIKE ? OR address LIKE ?)";
        $searchQuery = "%$query%";
        $params[] = $searchQuery;
        $params[] = $searchQuery;
        $types .= "ss";
    }
    
    // Lọc theo loại trạm sạc
    if ($type) {
        $sql .= " AND station_type = ?";
        $params[] = $type;
        $types .= "s";
    }
    
    // Lọc theo tiện ích
    if ($amenity) {
        $sql .= " AND amenities LIKE ?";
        $params[] = "%$amenity%";
        $types .= "s";
    }
    
    // Chỉ lấy trạm đang hoạt động
    $sql .= " AND status = 'available'";
    
    // Lọc theo khoảng cách nếu có
    if ($maxDistance && $userLat && $userLng) {
        $sql .= " HAVING distance <= ?";
        $params[] = $maxDistance;
        $types .= "d";
    }
    
    // Sắp xếp theo khoảng cách nếu có tọa độ
    if ($userLat && $userLng) {
        $sql .= " ORDER BY distance ASC";
    } else {
        $sql .= " ORDER BY station_name ASC";
    }
    
    $sql .= " LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";
    
    $stmt = $conn->prepare($sql);
    if ($types) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stations = [];
    while ($row = $result->fetch_assoc()) {
        // Chuyển đổi amenities từ string thành array
        $row['amenities'] = $row['amenities'] ? explode(',', $row['amenities']) : [];
        $row['distance'] = round($row['distance'], 1);
        $stations[] = $row;
    }
    
    // Đếm tổng số kết quả
    $countSql = str_replace("SELECT station_id, station_name, address, latitude, longitude, station_type, power_capacity, total_ports, available_ports, amenities, status, operating_hours, pricing", "SELECT COUNT(*)", 
                           str_replace(" LIMIT ? OFFSET ?", "", $sql));
    if ($userLat && $userLng) {
        $countSql = str_replace(", (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distance", "", $countSql);
        $countParams = array_slice($params, 3, -2); // Bỏ 3 param đầu (lat, lng, lat) và 2 param cuối (limit, offset)
        $countTypes = substr($types, 3, -2);
    } else {
        $countParams = array_slice($params, 0, -2);
        $countTypes = substr($types, 0, -2);
    }
    
    $countStmt = $conn->prepare($countSql);
    if ($countTypes) {
        $countStmt->bind_param($countTypes, ...$countParams);
    }
    $countStmt->execute();
    $totalCount = $countStmt->get_result()->fetch_row()[0];
    
    echo json_encode([
        'success' => true,
        'data' => $stations,
        'total' => intval($totalCount),
        'limit' => $limit,
        'offset' => $offset
    ]);
}

function getNearbyStations($conn, $userLat, $userLng, $limit, $offset) {
    if (!$userLat || !$userLng) {
        echo json_encode(['error' => 'Coordinates required']);
        return;
    }
    
    $sql = "SELECT 
                station_id, 
                station_name, 
                address, 
                latitude, 
                longitude, 
                station_type, 
                power_capacity, 
                total_ports, 
                available_ports, 
                amenities, 
                status, 
                operating_hours, 
                pricing,
                (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distance
            FROM charging_stations 
            WHERE status = 'available'
            ORDER BY distance ASC
            LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dddii", $userLat, $userLng, $userLat, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stations = [];
    while ($row = $result->fetch_assoc()) {
        $row['amenities'] = $row['amenities'] ? explode(',', $row['amenities']) : [];
        $row['distance'] = round($row['distance'], 1);
        $stations[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $stations,
        'total' => count($stations)
    ]);
}

function getAllStations($conn, $limit, $offset) {
    $sql = "SELECT 
                station_id, 
                station_name, 
                address, 
                latitude, 
                longitude, 
                station_type, 
                power_capacity, 
                total_ports, 
                available_ports, 
                amenities, 
                status, 
                operating_hours, 
                pricing,
                0 as distance
            FROM charging_stations 
            WHERE status = 'available'
            ORDER BY station_name ASC
            LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $stations = [];
    while ($row = $result->fetch_assoc()) {
        $row['amenities'] = $row['amenities'] ? explode(',', $row['amenities']) : [];
        $stations[] = $row;
    }
    
    // Đếm tổng số
    $countSql = "SELECT COUNT(*) FROM charging_stations WHERE status = 'available'";
    $countResult = $conn->query($countSql);
    $totalCount = $countResult->fetch_row()[0];
    
    echo json_encode([
        'success' => true,
        'data' => $stations,
        'total' => intval($totalCount),
        'limit' => $limit,
        'offset' => $offset
    ]);
}

function getStationDetail($conn, $stationId) {
    if (!$stationId) {
        echo json_encode(['error' => 'Station ID required']);
        return;
    }
    
    $sql = "SELECT * FROM charging_stations WHERE station_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $stationId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $row['amenities'] = $row['amenities'] ? explode(',', $row['amenities']) : [];
        echo json_encode([
            'success' => true,
            'data' => $row
        ]);
    } else {
        echo json_encode(['error' => 'Station not found']);
    }
}

$conn->close();
?>
