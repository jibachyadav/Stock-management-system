<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cat_id = intval($_POST['cat_id']);

    // Check if category is linked to any products
    $check_sql = "SELECT COUNT(*) as count FROM Product WHERE cat_id = $cat_id";
    $result = $conn->query($check_sql);
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        header("Location: ../../pages/category.php?error=Cannot delete category: Linked to " . $row['count'] . " products. Please delete products first.");
        exit();
    }

    $admin_id = $_SESSION['admin_id'];
    $sql = "DELETE FROM Category WHERE cat_id = $cat_id AND admin_id = $admin_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../pages/category.php?success=Category deleted successfully");
    } else {
        header("Location: ../../pages/category.php?error=Error deleting category: " . $conn->error);
    }
}
?>
