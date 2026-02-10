<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <title>Register - StockMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .auth-card {
      width: 100%;
      max-width: 500px;
      border: none;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      backdrop-filter: blur(10px);
      background: rgba(255, 255, 255, 0.9);
    }
    .auth-header {
      text-align: center;
      padding: 2rem 2rem 1rem;
    }
    .auth-logo {
      font-size: 3rem;
      color: #0d6efd;
    }
    .form-floating:focus-within {
      z-index: 2;
    }
  </style>
</head>
<body>

  <div class="container p-3">
    <div class="card auth-card mx-auto">
      <div class="auth-header">
        <div class="auth-logo mb-2">
          <i class="bi bi-box-seam"></i>
        </div>
        <h4 class="fw-bold">Create Account</h4>
        <p class="text-muted">Join StockMS today</p>
      </div>
      
      <div class="card-body p-4 pt-0">
        
        <?php if(isset($_GET['error'])): ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>
              <div><?php echo htmlspecialchars($_GET['error']); ?></div>
            </div>
        <?php endif; ?>

        <form action="../backend/auth/register.php" method="POST">
          
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <div class="input-group">
              <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
              <input type="text" name="name" class="form-control" placeholder="John Doe" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <div class="input-group">
              <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
              <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <div class="input-group">
              <span class="input-group-text bg-white"><i class="bi bi-telephone"></i></span>
              <input type="tel" name="phone" class="form-control" placeholder="1234567890">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
              <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
              <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Confirm Password</label>
            <div class="input-group">
              <span class="input-group-text bg-white"><i class="bi bi-check-lg"></i></span>
              <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
            </div>
          </div>

          <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
              Sign Up
            </button>
          </div>

          <div class="text-center">
            <p class="mb-0 text-muted">Already have an account? <a href="login.php" class="text-decoration-none fw-semibold">Login here</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
