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
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $admin_id = $_SESSION['admin_id'];
    $sql = "INSERT INTO Category (name, description, admin_id) VALUES ('$name', '$description', $admin_id)";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../pages/category.php?success=Category added successfully");
    } else {
        header("Location: ../../pages/category.php?error=Error adding category: " . $conn->error);
    }
}
?>
