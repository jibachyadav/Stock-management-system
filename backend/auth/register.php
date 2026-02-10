<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Basic Validation
    if ($password !== $confirm_password) {
        header("Location: ../../pages/register.php?error=Passwords do not match");
        exit();
    }

    // Check if email already exists
    $check_sql = "SELECT * FROM Admin WHERE email = '$email'";
    $check_result = $conn->query($check_sql);
    if ($check_result->num_rows > 0) {
        header("Location: ../../pages/register.php?error=Email already registered");
        exit();
    }

    // Hash Password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert User
    $sql = "INSERT INTO Admin (name, email, password, phone) VALUES ('$name', '$email', '$hashed_password', '$phone')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../../pages/login.php?success=Account created successfully! Please login.");
    } else {
        header("Location: ../../pages/register.php?error=Error creating account: " . $conn->error);
    }
}
?>
