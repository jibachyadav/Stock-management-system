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

    $admin_id = $_SESSION['admin_id'];
    $sql = "DELETE FROM Product WHERE prod_id = $prod_id AND admin_id = $admin_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../pages/product.php?success=Product deleted successfully");
    } else {
        header("Location: ../../pages/product.php?error=Error deleting product: " . $conn->error);
    }
}
?>
