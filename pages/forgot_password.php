<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password - StockMS</title>
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
      background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
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
      <h3 class="mb-1"><i class="bi bi-key me-2"></i>Forgot Password</h3>
      <p class="mb-0 opacity-75">Enter your email to reset your password</p>
    </div>
    <div class="card-body p-4 bg-white">
      
      <?php if(isset($_GET['error'])): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
      <?php endif; ?>
      <?php if(isset($_GET['success'])): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
      <?php endif; ?>

      <!-- INFO MESSAGE about local testing -->
      <?php if(isset($_GET['reset_link'])): ?>
          <div class="alert alert-warning">
              <strong><i class="bi bi-cone-striped me-2"></i>Local Testing Mode:</strong><br>
              Since email is not configured, click here to reset:<br>
              <a href="<?php echo htmlspecialchars($_GET['reset_link']); ?>" class="alert-link">Reset Password Link</a>
          </div>
      <?php endif; ?>

      <form action="../backend/auth/forgot_password_request.php" method="POST">
        <div class="mb-3">
          <label class="form-label fw-semibold">Email Address</label>
          <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" placeholder="admin@example.com" required>
          </div>
        </div>
        
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary py-2">
            Send Reset Link
          </button>
          <a href="login.php" class="btn btn-light text-muted">
            <i class="bi bi-arrow-left me-1"></i>Back to Login
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
