<?php

require_once __DIR__ . "../config/db_connect.php";
$data = $conn;


if ($data->connect_error) {
    die("Kết nối thất bại: " . $data->connect_error);
}

$sql = "SELECT product.status FROM product";

$result = mysqli_query($data, $sql);


if ($result) {

    while ($row = mysqli_fetch_assoc($result)) {
        echo "Status: " . $row['status'] . "<br>";
    }
} else {
    echo "Error: " . $sql . "<br>" . $data->error;
}


$data->close();
?>
