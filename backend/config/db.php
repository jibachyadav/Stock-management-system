<?php
define('SERVER', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', ''); 
define('DATABASE', 'stock_management');

$conn = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
