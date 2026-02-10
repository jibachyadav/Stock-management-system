<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_id = $_SESSION['admin_id'];
    $supplier_id = intval($_POST['supplier_id']);
    $supplier_name = mysqli_real_escape_string($conn, $_POST['supplier_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Check for duplicate name (excluding current supplier)
    $check = $conn->query("SELECT * FROM Supplier WHERE supplier_name = '$supplier_name' AND supplier_id != $supplier_id AND admin_id = $admin_id");
    if ($check->num_rows > 0) {
        header("Location: ../../pages/supplier.php?error=Supplier with this name already exists");
        exit();
    }

    // $admin_id defined above
    $sql = "UPDATE Supplier SET supplier_name = '$supplier_name', phone = '$phone', address = '$address' WHERE supplier_id = $supplier_id AND admin_id = $admin_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../pages/supplier.php?success=Supplier updated successfully");
    } else {
        header("Location: ../../pages/supplier.php?error=Error updating supplier: " . $conn->error);
    }
}
?>
