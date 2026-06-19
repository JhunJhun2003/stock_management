<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <title>POS Name - Product Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

 {{-- <link rel="stylesheet" href="product_management.css"> --}}
   <link href="{{ asset('css/product_management.css') }}" rel="stylesheet">

  </head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="sidebar-brand d-flex align-items-center justify-content-center gap-2">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" style="width: 32px; height: auto; object-fit: contain;">
                        <h5 class="fw-bold text-white m-0">POS Name</h5>
                    </div>

                    <ul class="nav flex-column gap-1">
                        <!-- Admin Menu Items -->
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('users.index') }}">
                                <i class="bi bi-people"></i> User Management
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link active" href="{{ route('products.index') }}">
                                <i class="bi bi-box-seam"></i> Product Management
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('reports.index') }}">
                                <i class="bi bi-graph-up"></i> Reports
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('settings.index') }}">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                        </li>

                        <!-- Seller Menu Items -->
                        <li class="nav-item seller-only {{ !Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('pos.index') }}">
                                <i class="bi bi-cpu"></i> POS (Sales)
                            </a>
                        </li>
                        <li class="nav-item seller-only {{ !Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('sales.history') }}">
                                <i class="bi bi-receipt"></i> Sales History
                            </a>
                        </li>
                        <li class="nav-item seller-only {{ !Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('settings.index') }}">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="sidebar-logout-container">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-0">Product Management</h4>
                        <small class="text-muted">Manage your inventory items and stock levels.</small>
                    </div>

                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="bi bi-plus-lg"></i> Add New Product
                    </button>
                </div>
                
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(isset($lowStockProducts) && $lowStockProducts->isNotEmpty())
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>Low Stock Alert:</strong>
                        The following products have stock below {{ \App\Models\Product::LOW_STOCK_THRESHOLD }} units —
                        @foreach($lowStockProducts as $lowStockProduct)
                            <span class="badge bg-warning text-dark ms-1">{{ $lowStockProduct->product_name }} ({{ $lowStockProduct->stock }} left)</span>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm p-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 16px">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Cost</th>
                                    <th>Selling Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td>{{ $product->product_code }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ ucfirst($product->category ?? 'N/A') }}</td>
                                    <td>{{ number_format($product->cost, 0) }} Ks</td>
                                    <td>{{ number_format($product->price, 0) }} Ks</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        @if($product->isOutOfStock())
                                            <span class="badge bg-danger-subtle text-danger">Out of Stock</span>
                                        @elseif($product->isLowStock())
                                            <span class="badge bg-warning-subtle text-warning">Low Stock</span>
                                        @else
                                            <span class="badge bg-success-subtle text-success">In Stock</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-light text-primary me-1 edit-product-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editProductModal"
                                            data-id="{{ $product->id }}"
                                            data-code="{{ $product->product_code }}"
                                            data-name="{{ $product->product_name }}"
                                            data-category="{{ $product->category }}"
                                            data-cost="{{ $product->cost }}"
                                            data-price="{{ $product->price }}"
                                            data-stock="{{ $product->stock }}"
                                            data-desc="{{ $product->description }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <button class="btn btn-sm btn-light text-danger delete-product-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteProductModal"
                                            data-id="{{ $product->id }}"
                                            data-code="{{ $product->product_code }}"
                                            data-name="{{ $product->product_name }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="bi bi-box-seam fs-1 d-block text-muted"></i>
                                        <p class="text-muted mt-2">No products found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if(isset($products) && method_exists($products, 'links'))
                    <div class="mt-3">
                        {{ $products->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" style="font-size: 18px">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="addProductForm" method="POST" action="{{ route('products.store') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Product Name *</label>
                                <input type="text" class="form-control" id="add_prod_name" name="product_name" placeholder="Enter product name" required style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Product Code *</label>
                                <input type="text" class="form-control bg-light" id="add_prod_code" name="product_code" readonly style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Category *</label>
                                <select class="form-select" id="add_prod_category" name="category" style="font-size: 16px" required>
                                    <option value="" disabled selected>Select category</option>
                                    <option value="drinks">Drinks</option>
                                    <option value="snacks">Snacks</option>
                                    <option value="grocery">Grocery</option>
                                    <option value="electronics">Electronics</option>
                                    <option value="clothing">Clothing</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Cost Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light" style="font-size: 14px">Ks.</span>
                                    <input type="number" class="form-control" id="add_prod_cost" name="cost" placeholder="0" required style="font-size: 16px" step="0.01" min="0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Selling Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light" style="font-size: 14px">Ks.</span>
                                    <input type="number" class="form-control" id="add_prod_price" name="price" placeholder="0" required style="font-size: 16px" step="0.01">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Initial Stock Quantity *</label>
                                <input type="number" class="form-control" id="add_prod_stock" name="stock" placeholder="0" required style="font-size: 16px" min="0">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Status</label>
                                <select class="form-select" id="add_prod_status" name="is_active" style="font-size: 16px">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-medium text-muted">Product Description (Optional)</label>
                                <textarea class="form-control" id="add_prod_desc" name="description" rows="2" placeholder="Enter short details..." style="font-size: 16px"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-top-0">
                        <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-3">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" style="font-size: 18px">Edit Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="editProductForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <input type="hidden" id="edit_product_id" name="product_id">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Product Name *</label>
                                <input type="text" class="form-control" id="edit_prod_name" name="product_name" required style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Product Code (SKU)</label>
                                <input type="text" class="form-control bg-light" id="edit_prod_code" name="product_code" readonly style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Category *</label>
                                <select class="form-select" id="edit_prod_category" name="category" style="font-size: 16px" required>
                                    <option value="drinks">Drinks</option>
                                    <option value="snacks">Snacks</option>
                                    <option value="grocery">Grocery</option>
                                    <option value="electronics">Electronics</option>
                                    <option value="clothing">Clothing</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Cost Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light" style="font-size: 14px">Ks.</span>
                                    <input type="number" class="form-control" id="edit_prod_cost" name="cost" required style="font-size: 16px" step="0.01" min="0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Selling Price *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light" style="font-size: 14px">Ks.</span>
                                    <input type="number" class="form-control" id="edit_prod_price" name="price" required style="font-size: 16px" step="0.01">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Current Stock Quantity *</label>
                                <input type="number" class="form-control" id="edit_prod_stock" name="stock" required style="font-size: 16px" min="0">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Status</label>
                                <select class="form-select" id="edit_prod_status" name="is_active" style="font-size: 16px">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-medium text-muted">Product Description (Optional)</label>
                                <textarea class="form-control" id="edit_prod_desc" name="description" rows="2" style="font-size: 16px"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-top-0">
                        <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-3">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Product Modal -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-box-seam text-danger fs-1 mb-3 d-block"></i>
                    <h5 class="fw-bold mb-2" style="font-size: 16px">Delete Product?</h5>
                    <p class="text-muted small mb-4">Are you sure you want to remove
                        <span id="delete_prod_name" class="fw-bold text-dark"></span> from
                        inventory? This action cannot be undone.
                    </p>
                    
                    <form id="deleteProductForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="delete_product_id" name="product_id">
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger btn-sm px-3">Confirm Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            // Get user role from Laravel
            const isAdmin = {{ Auth::user()->isAdmin() ? 'true' : 'false' }};
            const userRole = isAdmin ? 'admin' : 'seller';

            // If not admin, redirect to POS
            if (!isAdmin) {
                window.location.href = "{{ route('pos.index') }}";
                return;
            }

            // Show/hide menu items based on role
            if (userRole === "admin") {
                document.querySelectorAll(".seller-only").forEach(el => el.style.display = "none");
                document.querySelectorAll(".admin-only").forEach(el => el.style.display = "block");
            } else {
                document.querySelectorAll(".admin-only").forEach(el => el.style.display = "none");
                document.querySelectorAll(".seller-only").forEach(el => el.style.display = "block");
            }

            // Highlight active menu item
            const currentPath = window.location.pathname;
            document.querySelectorAll(".sidebar .nav-link").forEach(link => {
                const href = link.getAttribute("href");
                if (href && currentPath.includes(href)) {
                    link.classList.add("active");
                } else {
                    link.classList.remove("active");
                }
            });

            // Generate product code
            function generateProductCode() {
                const prefix = 'PRD-';
                const timestamp = Date.now().toString().slice(-6);
                const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
                return prefix + timestamp + random;
            }

            // Set product code when modal opens
            document.getElementById('addProductModal').addEventListener('show.bs.modal', function () {
                document.getElementById('add_prod_code').value = generateProductCode();
            });

            // Edit Product - Populate modal
            document.querySelectorAll('.edit-product-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const code = this.getAttribute('data-code');
                    const name = this.getAttribute('data-name');
                    const category = this.getAttribute('data-category');
                    const cost = this.getAttribute('data-cost');
                    const price = this.getAttribute('data-price');
                    const stock = this.getAttribute('data-stock');
                    const desc = this.getAttribute('data-desc');

                    document.getElementById('edit_product_id').value = id;
                    document.getElementById('edit_prod_code').value = code;
                    document.getElementById('edit_prod_name').value = name;
                    document.getElementById('edit_prod_category').value = category || '';
                    document.getElementById('edit_prod_cost').value = cost;
                    document.getElementById('edit_prod_price').value = price;
                    document.getElementById('edit_prod_stock').value = stock;
                    document.getElementById('edit_prod_desc').value = desc || '';

                    // Update form action
                    document.getElementById('editProductForm').action = `/products/${id}`;
                });
            });

            // Delete Product - Populate modal
            document.querySelectorAll('.delete-product-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    document.getElementById('delete_product_id').value = id;
                    document.getElementById('delete_prod_name').textContent = name;
                    document.getElementById('deleteProductForm').action = `/products/${id}`;
                });
            });
        });
    </script>
</body>
</html>
