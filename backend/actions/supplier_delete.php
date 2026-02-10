<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_id = intval($_POST['supplier_id']);

    // Check if supplier is linked to any products
    $check_sql = "SELECT COUNT(*) as count FROM Product WHERE supplier_id = $supplier_id";
    $result = $conn->query($check_sql);
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        header("Location: ../../pages/supplier.php?error=Cannot delete supplier: Linked to " . $row['count'] . " legacy products. Please delete products first.");
        exit();
    }

    $admin_id = $_SESSION['admin_id'];
    $sql = "DELETE FROM Supplier WHERE supplier_id = $supplier_id AND admin_id = $admin_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../pages/supplier.php?success=Supplier deleted successfully");
    } else {
        header("Location: ../../pages/supplier.php?error=Error deleting supplier: " . $conn->error);
    }
}
?>
