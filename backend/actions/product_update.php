<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prod_id = intval($_POST['prod_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $cat_id = intval($_POST['cat_id']);
    $supplier_id = intval($_POST['supplier_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $admin_id = $_SESSION['admin_id'];
    $sql = "UPDATE Product SET 
            name = '$name', 
            price = $price, 
            stock = $stock, 
            cat_id = $cat_id, 
            supplier_id = $supplier_id, 
            description = '$description' 
            WHERE prod_id = $prod_id AND admin_id = $admin_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../pages/product.php?success=Product updated successfully");
    } else {
        header("Location: ../../pages/product.php?error=Error updating product: " . $conn->error);
    }
}
?>
