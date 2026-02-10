<?php
include '../backend/auth/session_check.php';
include '../backend/config/db.php';


// Fetch Products for Dropdown
$admin_id = $_SESSION['admin_id'];
$prod_sql = "SELECT * FROM Product WHERE admin_id = $admin_id";
$prod_result = $conn->query($prod_sql);

// Fetch Transactions
$admin_id = $_SESSION['admin_id'];
$sql = "SELECT t.*, p.name as product_name, a.name as admin_name 
        FROM Stock_Transaction t 
        LEFT JOIN Product p ON t.prod_id = p.prod_id 
        LEFT JOIN Admin a ON t.admin_id = a.admin_id
        WHERE t.admin_id = $admin_id
        ORDER BY t.trans_date DESC";
$result = $conn->query($sql);

$_SESSION['form_token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <title>Stock Transactions - StockMS</title>
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
        <a href="stock_transaction.php" class="sidebar-link active">
          <i class="bi bi-arrow-left-right"></i> 
          <span>Stock</span>
        </a>
        <a href="admin.php" class="sidebar-link">
          <i class="bi bi-person"></i> 
          <span>Admin</span>
        </a>
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content flex-grow-1 p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold">
          <i class="bi bi-arrow-left-right me-2 text-danger"></i>Stock Transactions
        </h2>
        <button class="btn btn-danger" data-bs-toggle="collapse" data-bs-target="#addTransactionForm">
          <i class="bi bi-plus-circle me-2"></i>New Transaction
        </button>
      </div>

      <?php if(isset($_GET['success'])): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
      <?php endif; ?>
      <?php if(isset($_GET['error'])): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
      <?php endif; ?>

      <!-- Add Transaction Form -->
      <div class="card border-0 shadow-sm mb-4 collapse" id="addTransactionForm">
        <div class="card-header bg-white border-0 py-3">
          <h5 class="mb-0 fw-bold">
            <i class="bi bi-plus-square me-2 text-danger"></i>Add Stock Transaction
          </h5>
        </div>
        <div class="card-body">
          <form action="../backend/actions/stock_add.php" method="POST" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = 'Processing...';">
            <input type="hidden" name="form_token" value="<?php echo $_SESSION['form_token']; ?>">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label fw-semibold">Transaction Type</label>
                <select name="trans_type" class="form-select" required>
                  <option value="">Select Type</option>
                  <option value="IN">
                    IN (Add Stock)
                  </option>
                  <option value="OUT">
                    OUT (Remove Stock)
                  </option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold">Product</label>
                <select name="prod_id" class="form-select" required>
                  <option value="">Select Product</option>
                  <?php if($prod_result->num_rows > 0): ?>
                      <?php while($p = $prod_result->fetch_assoc()): ?>
                          <option value="<?php echo $p['prod_id']; ?>"><?php echo htmlspecialchars($p['name']); ?> (Stock: <?php echo $p['stock']; ?>)</option>
                      <?php endwhile; ?>
                  <?php endif; ?>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold">Quantity</label>
                <input type="number" name="quantity" class="form-control" placeholder="Enter quantity" min="1" required>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-danger">
                  <i class="bi bi-check-circle me-2"></i>Submit Transaction
                </button>
                <button type="reset" class="btn btn-outline-secondary ms-2">
                  <i class="bi bi-x-circle me-2"></i>Reset
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Transactions Table -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
              <i class="bi bi-list-ul me-2 text-primary"></i>Transaction History
            </h5>
            <div class="input-group" style="max-width: 300px;">
              <span class="input-group-text bg-white">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" class="form-control" placeholder="Search transactions...">
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-dark">
                <tr>
                   <th><i class="bi bi-hash me-1"></i>ID</th>
                   <th><i class="bi bi-calendar me-1"></i>Date</th>
                   <th><i class="bi bi-box me-1"></i>Product</th>
                   <th><i class="bi bi-arrow-left-right me-1"></i>Type</th>
                   <th><i class="bi bi-123 me-1"></i>Quantity</th>
                   <th><i class="bi bi-person me-1"></i>User</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $i = 1; while($row = $result->fetch_assoc()): ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php echo $row['trans_date']; ?></td>
                          <td><?php echo htmlspecialchars($row['product_name'] ?? 'Unknown Product'); ?></td>
                          <td>
                            <?php if($row['trans_type'] == 'IN'): ?>
                                <span class="badge bg-success">IN</span>
                            <?php else: ?>
                                <span class="badge bg-danger">OUT</span>
                            <?php endif; ?>
                          </td>
                          <td><?php echo $row['quantity']; ?></td>
                          <td><?php echo htmlspecialchars($row['admin_name'] ?? 'Unknown User'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No transactions found.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
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
