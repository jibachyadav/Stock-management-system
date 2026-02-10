<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Using Admin table as per schema
    $sql = "SELECT * FROM Admin WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Since this is a new implementation, we assume passwords are hashed. 
        // If the database is manually populated with plain text, this verify will fail unless we handle it.
        // For now, I will assume password_verify. 
        // NOTE: User needs to ensure the admin user is created with a hashed password.
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['role'] = 'admin'; // Assuming admin role
            session_regenerate_id(true); // Prevent session fixation
            header("Location: ../../index.php");
            exit();
        } else {
            header("Location: ../../pages/login.php?error=Invalid password!");
            exit();
        }
    } else {
        header("Location: ../../pages/login.php?error=User not found!");
        exit();
    }
} else {
    header("Location: ../../pages/login.php");
    exit();
}
?>
