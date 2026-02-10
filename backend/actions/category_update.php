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
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $admin_id = $_SESSION['admin_id'];
    $sql = "UPDATE Category SET name = '$name', description = '$description' WHERE cat_id = $cat_id AND admin_id = $admin_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../pages/category.php?success=Category updated successfully");
    } else {
        header("Location: ../../pages/category.php?error=Error updating category: " . $conn->error);
    }
}
?>
