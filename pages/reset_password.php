<?php
session_start();
include '../backend/config/db.php';

$token = $_GET['token'] ?? '';
$error = '';

if ($token) {
    // Validate Token securely (prepared statements better, but sticking to project style for consistency)
    $safe_token = mysqli_real_escape_string($conn, $token);
    $now = date('Y-m-d H:i:s');
    
    $result = $conn->query("SELECT * FROM Admin WHERE reset_token = '$safe_token' AND reset_expiry > '$now'");
    if ($result->num_rows === 0) {
        $error = "Invalid or expired token.";
    }
} else {
    $error = "No token provided.";
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <title>Reset Password - StockMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa !important;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .auth-card {
      max-width: 400px;
      margin: 80px auto;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .auth-header {
      background: linear-gradient(135deg, #198754 0%, #157347 100%);
      color: white;
      padding: 30px 20px;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="card auth-card border-0">
    <div class="auth-header">
      <h3 class="mb-1"><i class="bi bi-shield-lock me-2"></i>Reset Password</h3>
      <p class="mb-0 opacity-75">Create a new secure password</p>
    </div>
    <div class="card-body p-4 bg-white">
      
      <?php if($error): ?>
          <div class="alert alert-danger">
              <?php echo htmlspecialchars($error); ?><br>
              <a href="forgot_password.php" class="alert-link">Request a new link</a>
          </div>
      <?php else: ?>
          <form action="../backend/auth/reset_password_update.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            
            <div class="mb-3">
              <label class="form-label fw-semibold">New Password</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="6">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold">Confirm Password</label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required minlength="6">
              </div>
            </div>
            
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-success py-2">
                Update Password
              </button>
            </div>
          </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
