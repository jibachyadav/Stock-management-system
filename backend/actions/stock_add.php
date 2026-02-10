<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 0. STRICT DOUBLE-SUBMIT CHECK
    if (!isset($_POST['form_token']) || $_POST['form_token'] !== $_SESSION['form_token']) {
        // Token mismatch or already used (double click)
        header("Location: ../../pages/stock_transaction.php?error=Duplicate submission blocked!");
        exit();
    }
    // Consume the token immediately so it can't be used again
    unset($_SESSION['form_token']);

    $prod_id = intval($_POST['prod_id']);
    $trans_type = $_POST['trans_type']; // IN or OUT
    $quantity = intval($_POST['quantity']);
    $admin_id = $_SESSION['admin_id'];

    if ($quantity <= 0) {
        header("Location: ../../pages/stock_transaction.php?error=Quantity must be positive");
        exit();
    }

    // DEDUPLICATION CHECK: Prevent double-submit within 10 seconds
    $dup_sql = "SELECT trans_id FROM Stock_Transaction 
                WHERE prod_id = $prod_id 
                AND quantity = $quantity 
                AND trans_type = '$trans_type' 
                AND admin_id = $admin_id 
                AND trans_date > (NOW() - INTERVAL 10 SECOND)";
    
    $dup_result = $conn->query($dup_sql);
    
    if ($dup_result && $dup_result->num_rows > 0) {
        // Automatically assume success for the duplicate to avoid user confusion
        header("Location: ../../pages/stock_transaction.php?success=Transaction recorded successfully (Duplicate skipped)");
        exit();
    }

    // Start Transaction
    $conn->begin_transaction();

    try {
        // 1. Insert into Stock_Transaction
        $sql_insert = "INSERT INTO Stock_Transaction (trans_type, quantity, prod_id, admin_id) VALUES ('$trans_type', $quantity, $prod_id, $admin_id)";
        if (!$conn->query($sql_insert)) {
            throw new Exception("Error recording transaction: " . $conn->error);
        }

        // 2. Update Product Stock (HANDLED BY DB TRIGGER 'trg_update_stock_after_insert')
        // We only check for insufficient stock validity here before insert, but we don't manually update.
        
        if ($trans_type === 'OUT') {
            // Check if enough stock (Validation only)
            $check_stock = $conn->query("SELECT stock FROM Product WHERE prod_id = $prod_id");
            $current_stock = $check_stock->fetch_assoc()['stock'];
            if ($current_stock < $quantity) {
                 throw new Exception("Insufficient stock!");
            }
        }
        
        // No explicit UPDATE query needed. The Trigger does it.

        $conn->commit();
        header("Location: ../../pages/stock_transaction.php?success=Transaction recorded successfully");

    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../../pages/stock_transaction.php?error=" . urlencode($e->getMessage()));
    }
}
?>
