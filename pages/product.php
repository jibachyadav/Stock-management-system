<?php
include '../backend/auth/session_check.php';
include '../backend/config/db.php';


// Fetch Categories for Dropdown
$admin_id = $_SESSION['admin_id'];
$cat_sql = "SELECT * FROM Category WHERE admin_id = $admin_id";
$cat_result = $conn->query($cat_sql);

// Fetch Suppliers for Dropdown
$sup_sql = "SELECT * FROM Supplier WHERE admin_id = $admin_id";
$sup_result = $conn->query($sup_sql);

// Fetch Products with Joins
$admin_id = $_SESSION['admin_id'];
$sql = "SELECT p.*, c.name as category_name, s.supplier_name 
        FROM Product p 
        LEFT JOIN Category c ON p.cat_id = c.cat_id 
        LEFT JOIN Supplier s ON p.supplier_id = s.supplier_id
        WHERE p.admin_id = $admin_id
        ORDER BY p.prod_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <title>Products - StockMS</title>
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
        <a href="product.php" class="sidebar-link active">
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
        <a href="admin.php" class="sidebar-link">
          <i class="bi bi-person"></i> 
          <span>Admin</span>

          <form method="GET" action="product.php">
    <input type="text" name="search" placeholder="Search product..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
    <button type="submit">Search</button>
</form>

        </a>
      </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="content flex-grow-1 p-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold">
          <i class="bi bi-box me-2 text-primary"></i>Products
        </h2>
        <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#addProductForm">
          <i class="bi bi-plus-circle me-2"></i>Add Product
        </button>
      </div>

      <?php if(isset($_GET['success'])): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
      <?php endif; ?>
      <?php if(isset($_GET['error'])): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
      <?php endif; ?>

      <!-- Add Product Form -->
      <div class="card border-0 shadow-sm mb-4 collapse" id="addProductForm">
        <div class="card-header border-0 py-3">
          <h5 class="mb-0 fw-bold">
            <i class="bi bi-plus-square me-2 text-success"></i>Add New Product
          </h5>
        </div>
        <div class="card-body">
          <form action="../backend/actions/product_add.php" method="POST">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Product Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter product name" required>
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold">Price</label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" name="price" class="form-control" placeholder="0.00" step="0.01" required>
                </div>
              </div>
              <div class="col-md-3">
                <label class="form-label fw-semibold">Initial Stock</label>
                <input type="number" name="stock" class="form-control" placeholder="0" min="0" required>
              </div>
              
              <!-- Category Select with Quick Add -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">Category</label>
                <div class="input-group">
                    <select name="cat_id" id="cat_select" class="form-select" required>
                      <option value="">Select Category</option>
                      <?php if($cat_result->num_rows > 0): ?>
                          <?php while($c = $cat_result->fetch_assoc()): ?>
                              <option value="<?php echo $c['cat_id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                          <?php endwhile; ?>
                      <?php endif; ?>
                    </select>
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#quickAddCategoryModal">
                        <i class="bi bi-plus"></i> New
                    </button>
                </div>
              </div>

              <!-- Supplier Select with Quick Add -->
              <div class="col-md-6">
                <label class="form-label fw-semibold">Supplier</label>
                <div class="input-group">
                    <select name="supplier_id" id="supplier_select" class="form-select" required>
                      <option value="">Select Supplier</option>
                      <?php if($sup_result->num_rows > 0): ?>
                          <?php while($s = $sup_result->fetch_assoc()): ?>
                              <option value="<?php echo $s['supplier_id']; ?>"><?php echo htmlspecialchars($s['supplier_name']); ?></option>
                          <?php endwhile; ?>
                      <?php endif; ?>
                    </select>
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#quickAddSupplierModal">
                        <i class="bi bi-plus"></i> New
                    </button>
                </div>
              </div>

              <div class="col-12">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Enter description"></textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-success">
                  <i class="bi bi-check-circle me-2"></i>Add Product
                </button>
                <button type="reset" class="btn btn-outline-secondary ms-2">
                  <i class="bi bi-x-circle me-2"></i>Reset
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Products Table -->
      <div class="card border-0 shadow-sm">
        <div class="card-header border-0 py-3">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">
              <i class="bi bi-list-ul me-2 text-primary"></i>All Products
            </h5>
            <div class="input-group" style="max-width: 300px;">
              <span class="input-group-text">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" class="form-control" placeholder="Search products...">
            </div>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-dark">
                <tr>
                   <th><i class="bi bi-hash me-1"></i>ID</th>
                   <th><i class="bi bi-box me-1"></i>Name</th>
                   <th><i class="bi bi-currency-dollar me-1"></i>Price</th>
                   <th><i class="bi bi-box-seam me-1"></i>Stock</th>
                   <th><i class="bi bi-tag me-1"></i>Category</th>
                   <th><i class="bi bi-truck me-1"></i>Supplier</th>
                   <th><i class="bi bi-file-text me-1"></i>Description</th>
                   <th class="text-center" style="background: transparent !important; border: none !important;"></th>
                </tr>
              </thead>
              <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $i = 1; while($row = $result->fetch_assoc()): ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php echo htmlspecialchars($row['name']); ?></td>
                          <td><?php echo '$' . number_format($row['price'], 2); ?></td>
                          <td>
                              <span class="badge <?php echo $row['stock'] < 10 ? 'bg-danger' : 'bg-success'; ?>">
                                  <?php echo $row['stock']; ?>
                              </span>
                          </td>
                          <td><?php echo htmlspecialchars($row['category_name'] ?? ''); ?></td>
                          <td><?php echo htmlspecialchars($row['supplier_name'] ?? ''); ?></td>
                          <td><?php echo htmlspecialchars($row['description'] ?? ''); ?></td>
                          <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary me-1 edit-product-btn" 
                                data-id="<?php echo $row['prod_id']; ?>"
                                data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                data-price="<?php echo $row['price']; ?>"
                                data-stock="<?php echo $row['stock']; ?>"
                                data-cat="<?php echo $row['cat_id']; ?>"
                                data-sup="<?php echo $row['supplier_id']; ?>"
                                data-desc="<?php echo htmlspecialchars($row['description']); ?>"
                                data-bs-toggle="modal" data-bs-target="#editProductModal">
                              <i class="bi bi-pencil"></i>
                            </button>
                            <form action="../backend/actions/product_delete.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                <input type="hidden" name="prod_id" value="<?php echo $row['prod_id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                  <i class="bi bi-trash"></i>
                                </button>
                            </form>
                          </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No products found.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Quick Add Category Modal -->
<div class="modal fade" id="quickAddCategoryModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Quick Add Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="catAlert" class="alert d-none"></div>
        <div class="mb-3">
            <label class="form-label">Category Name</label>
            <input type="text" id="newCatName" class="form-control" placeholder="Enter name">
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea id="newCatDesc" class="form-control" rows="2" placeholder="Enter description"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveCategoryBtn">Save Category</button>
      </div>
    </div>
  </div>
</div>

<!-- Quick Add Supplier Modal -->
<div class="modal fade" id="quickAddSupplierModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Quick Add Supplier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="supAlert" class="alert d-none"></div>
        <div class="mb-3">
            <label class="form-label">Supplier Name</label>
            <input type="text" id="newSupName" class="form-control" placeholder="Enter name">
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" id="newSupPhone" class="form-control" placeholder="Enter phone">
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" id="newSupAddress" class="form-control" placeholder="Enter address">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveSupplierBtn">Save Supplier</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="../backend/actions/product_update.php" method="POST" id="editProductForm">
            <input type="hidden" name="prod_id" id="edit_prod_id">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label">Price</label>
                    <input type="number" name="price" id="edit_price" class="form-control" step="0.01" required>
                </div>
                <div class="col-6">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" id="edit_stock" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="cat_id" id="edit_cat_id" class="form-select" required>
                    <option value="">Select Category</option>
                    <?php 
                    // Reset pointer to reuse result set
                    $cat_result->data_seek(0);
                    while($c = $cat_result->fetch_assoc()): ?>
                        <option value="<?php echo $c['cat_id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Supplier</label>
                <select name="supplier_id" id="edit_supplier_id" class="form-select" required>
                    <option value="">Select Supplier</option>
                    <?php 
                    // Reset pointer to reuse result set
                    $sup_result->data_seek(0);
                    while($s = $sup_result->fetch_assoc()): ?>
                        <option value="<?php echo $s['supplier_id']; ?>"><?php echo htmlspecialchars($s['supplier_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" id="edit_description" class="form-control" rows="2"></textarea>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Theme and Sidebar Logic (Same as before)
  const themeToggle = document.getElementById('themeToggle'); // Note: Theme toggle button not explicitly in navbar here, but logic remains valid if added back
  const html = document.documentElement;
  const currentTheme = localStorage.getItem('theme') || 'light';
  html.setAttribute('data-bs-theme', currentTheme);
  
  // Sidebar logic...
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

  // --- Quick Add Logic ---

  // Category
  document.getElementById('saveCategoryBtn').addEventListener('click', function() {
      const name = document.getElementById('newCatName').value;
      const desc = document.getElementById('newCatDesc').value;
      const alertBox = document.getElementById('catAlert');
      
      const formData = new FormData();
      formData.append('name', name);
      formData.append('description', desc);

      fetch('../backend/actions/quick_add_category.php', {
          method: 'POST',
          body: formData
      })
      .then(response => response.json())
      .then(data => {
          if(data.success) {
              // Add to dropdown
              const select = document.getElementById('cat_select');
              const option = new Option(data.name, data.id);
              option.selected = true;
              select.add(option);
              
              // Close modal
              const modalEl = document.getElementById('quickAddCategoryModal');
              const modal = bootstrap.Modal.getInstance(modalEl);
              modal.hide();
              
              // Clear input
              document.getElementById('newCatName').value = '';
              document.getElementById('newCatDesc').value = '';
          } else {
              alertBox.textContent = data.error;
              alertBox.classList.remove('d-none', 'alert-success');
              alertBox.classList.add('alert-danger');
          }
      })
      .catch(error => {
          console.error('Error:', error);
      });
  });

  // Supplier
  document.getElementById('saveSupplierBtn').addEventListener('click', function() {
      const name = document.getElementById('newSupName').value;
      const phone = document.getElementById('newSupPhone').value;
      const address = document.getElementById('newSupAddress').value;
      const alertBox = document.getElementById('supAlert');
      
      const formData = new FormData();
      formData.append('supplier_name', name);
      formData.append('phone', phone);
      formData.append('address', address);

      fetch('../backend/actions/quick_add_supplier.php', {
          method: 'POST',
          body: formData
      })
      .then(response => response.json())
      .then(data => {
          if(data.success) {
              // Add to dropdown
              const select = document.getElementById('supplier_select');
              const option = new Option(data.supplier_name, data.id);
              option.selected = true;
              select.add(option);
              
              // Close modal
              const modalEl = document.getElementById('quickAddSupplierModal');
              const modal = bootstrap.Modal.getInstance(modalEl);
              modal.hide();
              
              // Clear input
              document.getElementById('newSupName').value = '';
              document.getElementById('newSupPhone').value = '';
              document.getElementById('newSupAddress').value = '';
          } else {
              alertBox.textContent = data.error;
              alertBox.classList.remove('d-none', 'alert-success');
              alertBox.classList.add('alert-danger');
          }
      })
      .catch(error => {
          console.error('Error:', error);
      });
  });


  // Edit Product Logic
  const editModal = document.getElementById('editProductModal');
  if (editModal) {
      editModal.addEventListener('show.bs.modal', event => {
          const button = event.relatedTarget;
          
          const id = button.getAttribute('data-id');
          const name = button.getAttribute('data-name');
          const price = button.getAttribute('data-price');
          const stock = button.getAttribute('data-stock');
          const cat = button.getAttribute('data-cat');
          const sup = button.getAttribute('data-sup');
          const desc = button.getAttribute('data-desc');
          
          document.getElementById('edit_prod_id').value = id;
          document.getElementById('edit_name').value = name;
          document.getElementById('edit_price').value = price;
          document.getElementById('edit_stock').value = stock;
          document.getElementById('edit_cat_id').value = cat;
          document.getElementById('edit_supplier_id').value = sup;
          document.getElementById('edit_description').value = desc;
      });
  }
</script>
</body>
</html>
