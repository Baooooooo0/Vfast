<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";


$conn = new mysqli($host,$user,$password,$db);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql = "SELECT product_id,product_name,product_price,color,product_number,status 
        FROM product";

$result = $conn->query($sql);

$data = array();
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }
}
//mã hóa dữ liệu dưới dạng json
$json_data = json_encode($data,JSON_PRETTY_PRINT);

file_put_contents('data_car.js',$json_data);

$conn->close();
?>

