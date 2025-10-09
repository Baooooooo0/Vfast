<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";


$data = new mysqli($host, $user, $password, $db);


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
