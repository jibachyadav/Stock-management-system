<?php
include '../backend/auth/session_check.php';
include '../backend/config/db.php';


$admin_id = $_SESSION['admin_id'];
$sql = "SELECT * FROM Supplier WHERE admin_id = $admin_id ORDER BY supplier_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <title>Suppliers - StockMS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
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
        <a href="supplier.php" class="sidebar-link active">
          <i class="bi bi-truck"></i> 
          <span>Suppliers</span>
        </a>
        <a href="stock_transaction.php" class="sidebar-link">
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
          <i class="bi bi-truck me-2 text-primary"></i>Suppliers
        </h2>
        <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#addSupplierForm">
          <i class="bi bi-plus-circle me-2"></i>Add Supplier
        </button>
      </div>

      <?php if(isset($_GET['success'])): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
      <?php endif; ?>
      <?php if(isset($_GET['error'])): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
      <?php endif; ?>

      <!-- Add Supplier Form -->
      <div class="card border-0 shadow-sm mb-4 collapse" id="addSupplierForm">
        <div class="card-header border-0 py-3">
          <h5 class="mb-0 fw-bold">
            <i class="bi bi-plus-square me-2 text-primary"></i>Add New Supplier
          </h5>
        </div>
        <div class="card-body">
          <form action="../backend/actions/supplier_add.php" method="POST">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Supplier Name</label>
                <input type="text" name="supplier_name" class="form-control" placeholder="Enter supplier name" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Phone</label>
                <input type="tel" name="phone" class="form-control" placeholder="Enter phone number" required>
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold">Address</label>
                <textarea name="address" class="form-control" rows="2" placeholder="Enter address"></textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary">
                  <i class="bi bi-check-circle me-2"></i>Add Supplier
                </button>
                <button type="reset" class="btn btn-outline-secondary ms-2">
                  <i class="bi bi-x-circle me-2"></i>Reset
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Suppliers Table -->
      <div class="card border-0 shadow-sm">
        <div class="card-header border-0 py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
              <i class="bi bi-list-ul me-2 text-primary"></i>All Suppliers
            </h5>
            <div class="input-group" style="max-width: 300px;">
              <span class="input-group-text">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" class="form-control" placeholder="Search suppliers...">
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-dark">
                <tr>
                  <th><i class="bi bi-hash me-1"></i>ID</th>
                  <th><i class="bi bi-building me-1"></i>Name</th>
                  <th><i class="bi bi-telephone me-1"></i>Phone</th>
                  <th><i class="bi bi-geo-alt me-1"></i>Address</th>
                  <th class="text-center" style="background: transparent !important; border: none !important;"></th>
                </tr>
              </thead>
              <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $i = 1; while($row = $result->fetch_assoc()): ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php echo htmlspecialchars($row['supplier_name'] ?? ''); ?></td>
                          <td><?php echo htmlspecialchars($row['phone'] ?? ''); ?></td>
                          <td><?php echo htmlspecialchars($row['address'] ?? ''); ?></td>
                          <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary me-1 edit-supplier-btn"
                                data-id="<?php echo $row['supplier_id']; ?>"
                                data-name="<?php echo htmlspecialchars($row['supplier_name']); ?>"
                                data-phone="<?php echo htmlspecialchars($row['phone']); ?>"
                                data-address="<?php echo htmlspecialchars($row['address']); ?>"
                                data-bs-toggle="modal" data-bs-target="#editSupplierModal">
                              <i class="bi bi-pencil"></i>
                            </button>
                            <form action="../backend/actions/supplier_delete.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                <input type="hidden" name="supplier_id" value="<?php echo $row['supplier_id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                  <i class="bi bi-trash"></i>
                                </button>
                            </form>
                          </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center">No suppliers found.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
    </main>
  </div>
</div>

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Supplier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="../backend/actions/supplier_update.php" method="POST">
            <input type="hidden" name="supplier_id" id="edit_supplier_id">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Supplier Name</label>
                <input type="text" name="supplier_name" id="edit_supplier_name" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="tel" name="phone" id="edit_phone" class="form-control" required>
              </div>
              <div class="col-12">
                <label class="form-label">Address</label>
                <textarea name="address" id="edit_address" class="form-control" rows="2"></textarea>
              </div>
              <div class="col-12 text-end">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const themeToggle = document.getElementById('themeToggle');
  const html = document.documentElement;
  const currentTheme = localStorage.getItem('theme') || 'light';
  html.setAttribute('data-bs-theme', currentTheme);
  if (themeToggle) {
    updateThemeIcon(currentTheme);
    themeToggle.addEventListener('click', () => {
      const currentTheme = html.getAttribute('data-bs-theme');
      const newTheme = currentTheme === 'light' ? 'dark' : 'light';
      html.setAttribute('data-bs-theme', newTheme);
      localStorage.setItem('theme', newTheme);
      updateThemeIcon(newTheme);
    });
  }
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

  // Edit Supplier Logic
  const editModal = document.getElementById('editSupplierModal');
  if (editModal) {
      editModal.addEventListener('show.bs.modal', event => {
          const button = event.relatedTarget;
          
          const id = button.getAttribute('data-id');
          const name = button.getAttribute('data-name');
          const phone = button.getAttribute('data-phone');
          const address = button.getAttribute('data-address');
          
          document.getElementById('edit_supplier_id').value = id;
          document.getElementById('edit_supplier_name').value = name;
          document.getElementById('edit_phone').value = phone;
          document.getElementById('edit_address').value = address;
      });
  }
</script>
</body>
</html>
