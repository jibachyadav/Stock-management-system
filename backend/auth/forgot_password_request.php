<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists
    $result = $conn->query("SELECT admin_id FROM Admin WHERE email = '$email'");
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $admin_id = $row['admin_id'];

        // Generate Token
        $token = bin2hex(random_bytes(32)); // 64 char random string
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Expires in 1 hour

        // SELF-HEALING: Check if columns exist, if not create them (Fixes 'Unknown column' error)
        $checkCol = $conn->query("SHOW COLUMNS FROM Admin LIKE 'reset_token'");
        if ($checkCol && $checkCol->num_rows == 0) {
            $conn->query("ALTER TABLE Admin ADD COLUMN reset_token VARCHAR(255) NULL");
            $conn->query("ALTER TABLE Admin ADD COLUMN reset_expiry DATETIME NULL");
        }

        // Save token to DB
        $sql = "UPDATE Admin SET reset_token = '$token', reset_expiry = '$expiry' WHERE admin_id = $admin_id";
        
        if ($conn->query($sql) === TRUE) {
            // "Send" Email
            // In a real app, use mail($email, "Reset", "Link: ...");
            // For local dev, redirect back with the link visible
            
            $reset_link = "reset_password.php?token=" . $token;
            // Link is relative to forgot_password.php where it is displayed
            
            header("Location: ../../pages/forgot_password.php?success=Reset link generated!&reset_link=" . urlencode($reset_link));
        } else {
            header("Location: ../../pages/forgot_password.php?error=Database error: " . $conn->error);
        }

    } else {
        // Email not found
        // Security: Don't reveal if email exists or not, but for this dev stage we might say "Email not found" for clarity
        header("Location: ../../pages/forgot_password.php?error=Email not found in our records.");
    }
}
?>
