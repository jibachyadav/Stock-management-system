<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Login - StockMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #121212; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 400px; padding: 20px; border-radius: 10px; background: #1e1e1e; border: 1px solid #333; }
    </style>
</head>
<body>

<div class="login-card shadow-lg">
    <div class="text-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-box-seam"></i> StockMS</h2>
        <p class="text-muted">Please login to your account</p>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger py-2"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <form method="POST" action="../backend/auth/login.php">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required placeholder="Enter email">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required placeholder="Enter password">
            <div class="text-end mt-1">
                <a href="forgot_password.php" class="text-decoration-none small">Forgot Password?</a>
            </div>
        </div>
    <button type="submit" class="btn btn-primary w-100 py-2 mt-2">Login</button>
    </form>
    <div class="text-center mt-3">
        <p class="text-muted small">Don't have an account? <a href="register.php" class="text-decoration-none">Create Account</a></p>
    </div>
</div>

</body>
</html>