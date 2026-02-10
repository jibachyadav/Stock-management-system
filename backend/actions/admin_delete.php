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

    // Start Transaction
    $conn->begin_transaction();

    try {
        // 1. Delete Stock Transactions
        if (!$conn->query("DELETE FROM Stock_Transaction WHERE admin_id = $admin_id")) {
             throw new Exception("Error deleting transactions: " . $conn->error);
        }

        // 2. Delete Products
        if (!$conn->query("DELETE FROM Product WHERE admin_id = $admin_id")) {
             throw new Exception("Error deleting products: " . $conn->error);
        }

        // 3. Delete Categories
        // 
        if (!$conn->query("DELETE FROM Category WHERE admin_id = $admin_id")) {
             throw new Exception("Error deleting categories: " . $conn->error);
        }

        // 4. Delete Suppliers
        if (!$conn->query("DELETE FROM Supplier WHERE admin_id = $admin_id")) {
             throw new Exception("Error deleting suppliers: " . $conn->error);
        }

        // 5. Delete Admin
        if (!$conn->query("DELETE FROM Admin WHERE admin_id = $admin_id")) {
             throw new Exception("Error deleting account: " . $conn->error);
        }

        // Commit Logic
        $conn->commit();

        // Destroy Session
        session_unset();
        session_destroy();

        // Redirect to Login with message (using query param that login page might show)
        header("Location: ../../pages/login.php?msg=Account deleted successfully");

    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../../pages/admin.php?error=Delete failed: " . urlencode($e->getMessage()));
    }
}
?>
