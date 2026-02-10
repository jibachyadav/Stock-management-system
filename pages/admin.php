
<?php
include '../backend/auth/session_check.php';
include '../backend/config/db.php';


$admin_id = $_SESSION['admin_id'];
$admin = $conn->query("SELECT * FROM Admin WHERE admin_id = $admin_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <title>Admin - StockMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="d-flex flex-column min-vh-100">
  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="../index.php">
        <i class="bi bi-box-seam me-2"></i>StockMS
      </a>
      <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item me-3 d-flex align-items-center text-white">
                Welcome, <?php echo $_SESSION['admin_name'] ?? 'Admin'; ?>
            </li>
            <li class="nav-item">
                <a href="../backend/auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Sidebar Overlay for Mobile -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <div class="d-flex flex-grow-1">
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header text-center mb-4 py-3">
        <h4 class="mb-0 fw-bold text-white">
          <i class="bi bi-speedometer2 me-2"></i>StockMS
        </h4>
      </div>
      <nav class="sidebar-nav">
        <a href="../index.php" class="sidebar-link">
          <i class="bi bi-speedometer2"></i> 
          <span>Dashboard</span>
        </a>
        <a href="category.php" class="sidebar-link">
          <i class="bi bi-tags"></i> 
          <span>Categories</span>
        </a>
        <a href="product.php" class="sidebar-link">
          <i class="bi bi-box"></i> 
          <span>Products</span>
        </a>
        <a href="supplier.php" class="sidebar-link">
          <i class="bi bi-truck"></i> 
          <span>Suppliers</span>
        </a>
        <a href="stock_transaction.php" class="sidebar-link">
          <i class="bi bi-arrow-left-right"></i> 
          <span>Stock</span>
        </a>
        <a href="admin.php" class="sidebar-link active">
          <i class="bi bi-person"></i> 
          <span>Admin</span>
        </a>
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content flex-grow-1 p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold">
          <i class="bi bi-person-circle me-2 text-dark"></i>Admin Profile
        </h2>
      </div>

      <?php if(isset($_GET['success'])): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
      <?php endif; ?>
      <?php if(isset($_GET['error'])): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
      <?php endif; ?>

      <div class="row g-4">
        <div class="col-md-4">
          <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-5">
              <div class="mb-3">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <?php if (!empty($admin['image'])): ?>
                        <img src="../<?php echo htmlspecialchars($admin['image']); ?>" alt="Admin Profile" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    <?php else: ?>
                        <i class="bi bi-person-circle" style="font-size: 5rem; color: #6c757d;"></i>
                    <?php endif; ?>
                </div>
                <h4 class="fw-bold text-center"><?php echo htmlspecialchars($admin['name']); ?></h4>
                <p class="text-muted mb-0 text-center"><?php echo htmlspecialchars($admin['email']); ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
              <h5 class="mb-0 fw-bold">
                <i class="bi bi-gear me-2 text-primary"></i>Profile Settings
              </h5>
            </div>
            <div class="card-body">
              <form action="../backend/actions/admin_update.php" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                  <label class="form-label fw-semibold">Profile Photo</label>
                  <input type="file" class="form-control" name="image" accept="image/*">
                </div>

                <div class="mb-3">
                  <label class="form-label fw-semibold">Admin Name</label>
                  <input type="text" class="form-control"
                         name="name"
                         value="<?php echo htmlspecialchars($admin['name']); ?>"
                         placeholder="Enter admin name" required>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-semibold">Email</label>
                  <input type="email" class="form-control"
                         name="email"
                         value="<?php echo htmlspecialchars($admin['email']); ?>"
                         placeholder="Enter email" required>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-semibold">New Password (leave blank to keep current)</label>
                  <div class="input-group">
                    <input type="password" class="form-control"
                           name="password"
                           placeholder="Enter new password">
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-semibold">Confirm New Password</label>
                  <input type="password" class="form-control"
                         name="confirm_password"
                         placeholder="Confirm password">
                </div>

                <button type="submit" class="btn btn-dark">
                  <i class="bi bi-check-circle me-2"></i>Update Profile
                </button>

              </form>
            </div>
          </div>
          
          <!-- Danger Zone -->
          <div class="card border-danger shadow-sm mt-4">
            <div class="card-header bg-danger text-white border-0 py-3">
              <h5 class="mb-0 fw-bold">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>Danger Zone
              </h5>
            </div>
            <div class="card-body">
              <p class="text-muted">Deleting your account is permanent. This will wipe all your data including:</p>
              <ul class="text-danger small mb-4">
                  <li>Your Admin Profile</li>
                  <li>All your Products & Stock History</li>
                  <li>All Categories you created</li>
                  <li>All Suppliers you added</li>
              </ul>
              <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                <i class="bi bi-trash3 me-2"></i>Delete My Account
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Delete Account?</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="fw-bold fs-5 text-center">Are you absolutely sure?</p>
          <p class="text-center">This action cannot be undone. All your data will be permanently removed.</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <form action="../backend/actions/admin_delete.php" method="POST">
             <button type="submit" class="btn btn-danger">Yes, Delete Everything</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="footer bg-dark text-white mt-auto py-3">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start">
          <p class="mb-0">
            <i class="bi bi-box-seam me-2"></i>
            <strong>StockMS</strong> - Stock Management System
          </p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <p class="mb-0">
            <small>&copy; 2024 StockMS. All rights reserved.</small>
          </p>
        </div>
      </div>
    </div>
  </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const themeToggle = document.getElementById('themeToggle');
  const html = document.documentElement;
  const currentTheme = localStorage.getItem('theme') || 'light';
  html.setAttribute('data-bs-theme', currentTheme);
  updateThemeIcon(currentTheme);
  themeToggle.addEventListener('click', () => {
    const currentTheme = html.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    html.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateThemeIcon(newTheme);
  });
  function updateThemeIcon(theme) {
    const icon = themeToggle.querySelector('i');
    icon.className = theme === 'light' ? 'bi bi-moon-stars' : 'bi bi-sun';
  }
  // Sidebar Toggle for Mobile
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');
  const sidebarOverlay = document.getElementById('sidebarOverlay');
  
  function toggleSidebar() {
    if(sidebar) sidebar.classList.toggle('show');
    if(sidebarOverlay) sidebarOverlay.classList.toggle('show');
    document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
  }
  
  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      toggleSidebar();
    });
  }
  
  if (sidebarOverlay) {
    sidebarOverlay.addEventListener('click', () => {
      sidebar.classList.remove('show');
      sidebarOverlay.classList.remove('show');
      document.body.style.overflow = '';
    });
  }
  
  document.querySelectorAll('.sidebar-link').forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth < 992) {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
        document.body.style.overflow = '';
      }
    });
  });
</script>
</body>
</html>
