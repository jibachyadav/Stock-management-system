<?php
define('IS_ROOT', true);
include 'backend/auth/session_check.php';
include 'backend/config/db.php';


// Stats Queries
$admin_id = $_SESSION['admin_id'];
$total_products = $conn->query("SELECT COUNT(*) as count FROM Product WHERE admin_id = $admin_id")->fetch_assoc()['count'];
$total_categories = $conn->query("SELECT COUNT(*) as count FROM Category WHERE admin_id = $admin_id")->fetch_assoc()['count'];
$total_suppliers = $conn->query("SELECT COUNT(*) as count FROM Supplier WHERE admin_id = $admin_id")->fetch_assoc()['count'];
$total_transactions = $conn->query("SELECT COUNT(*) as count FROM Stock_Transaction WHERE admin_id = $admin_id")->fetch_assoc()['count'];
$low_stock = $conn->query("SELECT COUNT(*) as count FROM Product WHERE stock < 10 AND admin_id = $admin_id")->fetch_assoc()['count'];

$recent_activity = $conn->query("SELECT t.*, p.name as product_name 
                                 FROM Stock_Transaction t 
                                 JOIN Product p ON t.prod_id = p.prod_id 
                                 WHERE t.admin_id = $admin_id
                                 ORDER BY t.trans_date DESC LIMIT 5");

?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <title>Stock Management Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css?v=2">

</head>
<body>

<div class="d-flex flex-column min-vh-100">
  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="index.php">
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
                <a href="backend/auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
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
        <a href="index.php" class="sidebar-link active">
          <i class="bi bi-speedometer2"></i> 
          <span>Dashboard</span>
        </a>
        <a href="pages/category.php" class="sidebar-link">
          <i class="bi bi-tags"></i> 
          <span>Categories</span>
        </a>
        <a href="pages/product.php" class="sidebar-link">
          <i class="bi bi-box"></i> 
          <span>Products</span>
        </a>
        <a href="pages/supplier.php" class="sidebar-link">
          <i class="bi bi-truck"></i> 
          <span>Suppliers</span>
        </a>
        <a href="pages/stock_transaction.php" class="sidebar-link">
          <i class="bi bi-arrow-left-right"></i> 
          <span>Stock</span>
        </a>
        <a href="pages/admin.php" class="sidebar-link">
          <i class="bi bi-person"></i> 
          <span>Admin</span>
        </a>
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content flex-grow-1 p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold">Dashboard</h2>
        <div class="text-muted">
          <i class="bi bi-calendar3 me-2"></i>
          <span id="currentDate"></span>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-md-3">
          <div class="card card-stat bg-primary text-white border-0 shadow-lg h-100 hover-lift">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-uppercase mb-2 opacity-75">Total Products</h6>
                  <h2 class="mb-0 fw-bold"><?php echo $total_products; ?></h2>
                </div>
                <div class="stat-icon">
                  <i class="bi bi-box-seam fs-1 opacity-50"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="card card-stat bg-success text-white border-0 shadow-lg h-100 hover-lift">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-uppercase mb-2 opacity-75">Categories</h6>
                  <h2 class="mb-0 fw-bold"><?php echo $total_categories; ?></h2>
                </div>
                <div class="stat-icon">
                  <i class="bi bi-tags fs-1 opacity-50"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="card card-stat bg- text-white border-0 -lg h-100 hover-right">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-uppercase mb-2 opacity-75">Suppliers</h6>
                  <h2 class="mb-0 fw-bold"><?php echo $total_suppliers; ?></h2>
                </div>
                <div class="stat-icon">
                  <i class="bi bi-truck fs-1 opacity-50"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="card card-stat bg-danger text-white border-0 shadow-lg h-100 hover-lift">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="text-uppercase mb-2 opacity-75">Low Stock</h6>
                  <h2 class="mb-0 fw-bold"><?php echo $low_stock; ?></h2>
                </div>
                <div class="stat-icon">
                  <i class="bi bi-exclamation-triangle fs-1 opacity-50"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="row g-4">
        <div class="col-12 col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 py-3">
              <h5 class="mb-0 fw-bold">
                <i class="bi bi-lightning-charge text-warning me-2"></i>Quick Actions
              </h5>
            </div>
            <div class="card-body">
              <div class="d-grid gap-2">
                <a href="pages/product.php" class="btn btn-outline-primary">
                  <i class="bi bi-plus-circle me-2"></i>Add New Product
                </a>
                <a href="pages/category.php" class="btn btn-outline-success">
                  <i class="bi bi-tag me-2"></i>Add Category
                </a>
                <a href="pages/supplier.php" class="btn btn-outline-info">
                  <i class="bi bi-truck me-2"></i>Add Supplier
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header border-0 py-3">
              <h5 class="mb-0 fw-bold">
                <i class="bi bi-graph-up text-success me-2"></i>Recent Activity
              </h5>
            </div>
            <div class="card-body">
              <div class="list-group list-group-flush">
                <?php if($recent_activity->num_rows > 0): ?>
                    <?php while($act = $recent_activity->fetch_assoc()): ?>
                        <div class="list-group-item border-0 px-0">
                          <small class="text-muted"><?php echo $act['trans_date']; ?></small>
                          <p class="mb-0">
                              <?php echo ($act['trans_type'] == 'IN' ? 'Stock IN' : 'Stock OUT') . ': ' . htmlspecialchars($act['product_name']) . ' (' . $act['quantity'] . ')'; ?>
                          </p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="list-group-item border-0 px-0">No recent activity.</div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
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
  // Wait for DOM to be fully loaded
  document.addEventListener('DOMContentLoaded', function() {
    // Theme Toggle
    const themeToggle = document.getElementById('themeToggle');
    const html = document.documentElement;
    
    if (themeToggle) {
      // Check for saved theme preference
      const currentTheme = localStorage.getItem('theme') || 'light';
      html.setAttribute('data-bs-theme', currentTheme);
      updateThemeIcon(currentTheme);
      
      themeToggle.addEventListener('click', function() {
        const currentTheme = html.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        html.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
      });
      
      function updateThemeIcon(theme) {
        const icon = themeToggle.querySelector('i');
        if (icon) {
          icon.className = theme === 'light' ? 'bi bi-moon-stars' : 'bi bi-sun';
        }
      }
    }
    
    // Sidebar Toggle for Mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    function toggleSidebar() {
      if (sidebar && sidebarOverlay) {
        const isShowing = sidebar.classList.contains('show');
        if (isShowing) {
          sidebar.classList.remove('show');
          sidebarOverlay.classList.remove('show');
          document.body.style.overflow = '';
        } else {
          sidebar.classList.add('show');
          sidebarOverlay.classList.add('show');
          document.body.style.overflow = 'hidden';
        }
      }
    }
    
    if (sidebarToggle) {
      sidebarToggle.removeAttribute('data-bs-toggle');
      sidebarToggle.removeAttribute('data-bs-target');
      
      sidebarToggle.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        toggleSidebar();
        return false;
      });
    }
    
    // Close sidebar when clicking overlay
    if (sidebarOverlay) {
      sidebarOverlay.addEventListener('click', function() {
        if (sidebar) {
          sidebar.classList.remove('show');
          sidebarOverlay.classList.remove('show');
          document.body.style.overflow = '';
        }
      });
    }
    
    // Close sidebar when clicking on a link (mobile)
    document.querySelectorAll('.sidebar-link').forEach(function(link) {
      link.addEventListener('click', function() {
        if (window.innerWidth < 992) {
          if (sidebar && sidebarOverlay) {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = '';
          }
        }
      });
    });
    
    // Current Date
    const currentDateEl = document.getElementById('currentDate');
    if (currentDateEl) {
      currentDateEl.textContent = new Date().toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    }
  });
</script>
</body>
</html>
