<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        header("Location: ../../pages/reset_password.php?token=$token&error=Passwords do not match");
        exit();
    }

    if (strlen($password) < 6) {
        header("Location: ../../pages/reset_password.php?token=$token&error=Password must be at least 6 characters");
        exit();
    }

    // Verify token one last time
    $now = date('Y-m-d H:i:s');
    $check = $conn->query("SELECT * FROM Admin WHERE reset_token = '$token' AND reset_expiry > '$now'");

    if ($check->num_rows > 0) {
        // Update Password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Clear token too so it can't be reused
        $sql = "UPDATE Admin SET password = '$hashed_password', reset_token = NULL, reset_expiry = NULL WHERE reset_token = '$token'";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../../pages/login.php?success=Password updated successfully! Please login.");
        } else {
            header("Location: ../../pages/reset_password.php?token=$token&error=Error updating password: " . $conn->error);
        }
    } else {
        header("Location: ../../pages/reset_password.php?error=Invalid or expired token");
    }
}
?>
