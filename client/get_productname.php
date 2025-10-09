<?php 
$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";

$data = mysqli_connect($host, $user, $password, $db);

if (!$data) {
    die("Connection failed: " . mysqli_connect_error());
}

$product_name = $_GET['product_name'];

// Query to get product price and availability
$sql_products = "SELECT FORMAT(product_price, 0) as product_price, SUM(product_number) as total_stock FROM product WHERE product_name = ?";
$stmt = $data->prepare($sql_products);
$stmt->bind_param('s', $product_name);
$stmt->execute();
$result = $stmt->get_result();
$price = null;
$totalStock = 0;

if ($row = mysqli_fetch_assoc($result)) {
    $price = $row['product_price'];
    $totalStock = $row['total_stock']; // Total stock for the product
}

// Query to get colors and their stock status
$sql_color_products = "SELECT color, SUM(product_number) as color_stock FROM product WHERE product_name = ? GROUP BY color";
$stmt1 = $data->prepare($sql_color_products);
$stmt1->bind_param('s', $product_name);
$stmt1->execute();
$result1 = $stmt1->get_result();
$colors = [];
while ($row1 = mysqli_fetch_assoc($result1)) {
    $colors[] = [
        'color' => $row1['color'],
        'in_stock' => $row1['color_stock'] > 0 
    ];
}

$response = ['price' => $price, 'colors' => $colors, 'inStock' => $totalStock > 0];
header('Content-Type: application/json');
echo json_encode($response);

mysqli_close($data);
?>
