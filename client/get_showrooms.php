<?php 

$host = "localhost";
$user = "root";
$password = "";
$db = "carshop";

$data = mysqli_connect($host, $user, $password, $db);

if (!$data) {
    die("Connection failed: " . mysqli_connect_error());
}

$place = $_GET['place'];


$sql_showrooms = "SELECT location_address FROM locations WHERE location_name = ?";
$stmt = $data->prepare($sql_showrooms);
$stmt->bind_param('s', $place);
$stmt->execute();
$result = $stmt->get_result();

$showrooms = [];
while ($row = mysqli_fetch_assoc($result)) {
    $showrooms[] = $row['location_address'];
}

echo json_encode($showrooms);


mysqli_close($data);
?>
