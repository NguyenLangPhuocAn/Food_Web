<?php
$host = "localhost";
$username = "root";
$password = "Legendary123";
$dbname = "food_db"; 

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>