<?php
session_start();
include '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['supplier_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // Check if empty
    if(empty($name)) {
        echo json_encode(['success' => false, 'error' => 'Supplier name is required']);
        exit();
    }

    // Check for duplicate
    $admin_id = $_SESSION['admin_id'];
    $check = $conn->query("SELECT * FROM Supplier WHERE supplier_name = '$name' AND admin_id = $admin_id");
    if ($check->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'Supplier with this name already exists']);
        exit();
    }

    // Insert new supplier
    $sql = "INSERT INTO Supplier (supplier_name, phone, address, admin_id) VALUES ('$name', '$phone', '$address', $admin_id)";

    if ($conn->query($sql) === TRUE) {
        $new_id = $conn->insert_id;
        echo json_encode([
            'success' => true, 
            'id' => $new_id, 
            'supplier_name' => $name,
            'message' => 'Supplier added successfully'
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
?>
