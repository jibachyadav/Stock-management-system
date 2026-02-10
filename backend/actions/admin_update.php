<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $admin_id = (int) $_SESSION['admin_id'];
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    
    $sql_update_image = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $target_dir = "../../images/admin/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_tmp  = $_FILES['image']['tmp_name'];
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];

        
        $allowed_ext  = ['jpg', 'jpeg', 'png'];
        $allowed_mime = ['image/jpeg', 'image/png',];

        
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed_ext)) {
            header("Location: ../../pages/admin.php?error=Only JPG, JPEG, PNG, WEBP images allowed");
            exit();
        }

        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $file_tmp);
        finfo_close($finfo);

        if (!in_array($mime, $allowed_mime)) {
            header("Location: ../../pages/admin.php?error=Invalid image file");
            exit();
        }

    
        if ($file_size > 2 * 1024 * 1024) {
            header("Location: ../../pages/admin.php?error=Image must be under 2MB");
            exit();
        }

        
        $new_filename = "admin_" . $admin_id . "_" . time() . "." . $file_ext;
        $target_file  = $target_dir . $new_filename;

        if (move_uploaded_file($file_tmp, $target_file)) {
            $image_path = "images/admin/" . $new_filename;
            $sql_update_image = ", image='$image_path'";
        }
    }

    
    $sql = "UPDATE Admin 
            SET name='$name', email='$email' $sql_update_image 
            WHERE admin_id=$admin_id";

    try {

        if ($conn->query($sql) === TRUE) {

            $_SESSION['admin_name'] = $name;

        
            if (!empty($password)) {

                if ($password !== $confirm_password) {
                    header("Location: ../../pages/admin.php?error=Passwords do not match");
                    exit();
                }

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql_pass = "UPDATE Admin SET password='$hashed_password' WHERE admin_id=$admin_id";

                if (!$conn->query($sql_pass)) {
                    header("Location: ../../pages/admin.php?error=Password update failed");
                    exit();
                }
            }

            header("Location: ../../pages/admin.php?success=Profile updated successfully");
            exit();
        }

    } catch (mysqli_sql_exception $e) {

        
        if (strpos($e->getMessage(), "Unknown column 'image'") !== false) {

            $conn->query("ALTER TABLE Admin ADD COLUMN image VARCHAR(255) DEFAULT NULL");

            if ($conn->query($sql) === TRUE) {
                $_SESSION['admin_name'] = $name;
                header("Location: ../../pages/admin.php?success=Profile updated (DB upgraded)");
                exit();
            }
        }

        header("Location: ../../pages/admin.php?error=Update failed");
        exit();
    }
}
?>
