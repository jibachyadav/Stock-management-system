<?php
session_start();
include '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
    
    // Check if empty
    if(empty($name)) {
        echo json_encode(['success' => false, 'error' => 'Name is required']);
        exit();
    }

    $admin_id = $_SESSION['admin_id'];

    // Check for duplicate
    $check = $conn->query("SELECT * FROM Category WHERE name = '$name' AND admin_id = $admin_id");
    if ($check->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'Category with this name already exists']);
        exit();
    }
    $sql = "INSERT INTO Category (name, description, admin_id) VALUES ('$name', '$description', $admin_id)";

    if ($conn->query($sql) === TRUE) {
        $new_id = $conn->insert_id;
        echo json_encode([
            'success' => true, 
            'id' => $new_id, 
            'name' => $name,
            'message' => 'Category added successfully'
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
?>
