<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $cat_id = intval($_POST['cat_id']);
    $supplier_id = intval($_POST['supplier_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    // Image handling omitted for simplicity, can be added later

    $admin_id = $_SESSION['admin_id'];
    $sql = "INSERT INTO Product (name, price, stock, cat_id, supplier_id, description, admin_id) VALUES ('$name', $price, $stock, $cat_id, $supplier_id, '$description', $admin_id)";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../pages/product.php?success=Product added successfully");
    } else {
        header("Location: ../../pages/product.php?error=Error adding product: " . $conn->error);
    }
}
?>
