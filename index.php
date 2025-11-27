<?php
require_once __DIR__ . "/config/db_connect.php";

$res = $conn->query("SELECT COUNT(*) FROM product");

$row = $res->fetch_row();

echo "DB OK — Product count = " . $row[0];
exit;
