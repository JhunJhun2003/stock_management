<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS Name - User Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('css/user_management.css') }}" rel="stylesheet">

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="sidebar-brand d-flex align-items-center justify-content-center gap-2">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo"
                            style="width: 32px; height: auto; object-fit: contain;">
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
                            <a class="nav-link active" href="{{ route('users.index') }}">
                                <i class="bi bi-people"></i> User Management
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('products.index') }}">
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
                        <h4 class="fw-bold mb-0">User Management</h4>
                        <small class="text-muted">Manage sales persons and their accounts.</small>
                    </div>

                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-plus-lg"></i> Add New User
                    </button>
                </div>

                <!-- Success/Error Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm p-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 16px">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>#{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->isAdmin())
                                                <span class="badge bg-primary">Administrator</span>
                                            @else
                                                <span class="badge bg-info">Sales Person</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-light text-primary me-1 edit-user-btn"
                                                data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                data-id="{{ $user->id }}" 
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-role="{{ $user->isAdmin() ? 'admin' : 'seller' }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            @if ($user->id !== Auth::id())
                                                <button class="btn btn-sm btn-light text-danger delete-user-btn"
                                                    data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                                                    data-id="{{ $user->id }}" 
                                                    data-name="{{ $user->name }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="bi bi-people fs-1 d-block text-muted"></i>
                                            <p class="text-muted mt-2">No users found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if (isset($users) && method_exists($users, 'links'))
                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" style="font-size: 18px">Add New User Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="addUserForm" method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="modal-body p-4">
                        @if ($errors->any() && old('user_id') === null)
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Full Name *</label>
                                <input type="text" class="form-control" id="add_fullname" name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Enter full name" required style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Email *</label>
                                <input type="email" class="form-control" id="add_email" name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Enter email" required style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Password *</label>
                                <input type="password" class="form-control" id="add_password" name="password"
                                    placeholder="Enter password (min 8 chars)" required style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">User Role *</label>
                                <select class="form-select" id="add_role" name="is_admin" style="font-size: 16px" required>
                                    <option value="" selected disabled>Select user role</option>
                                    <option value="1" {{ old('is_admin') === '1' ? 'selected' : '' }}>Administrator</option>
                                    <option value="0" {{ old('is_admin') === '0' ? 'selected' : '' }}>Sales Person (Seller)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-top-0">
                        <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-3">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold" style="font-size: 18px">Edit User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <input type="hidden" id="edit_user_id" name="user_id">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Full Name *</label>
                                <input type="text" class="form-control" id="edit_fullname" name="name"
                                    required style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">Email *</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required
                                    style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">New Password (optional)</label>
                                <input type="password" class="form-control" id="edit_password" name="password"
                                    placeholder="Leave blank to keep current" style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">User Role *</label>
                                <select class="form-select" id="edit_role" name="is_admin" style="font-size: 16px" required>
                                    <option value="1">Administrator</option>
                                    <option value="0">Sales Person (Seller)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-top-0">
                        <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-3">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-exclamation-circle text-danger fs-1 mb-3 d-block"></i>
                    <h5 class="fw-bold mb-2" style="font-size: 16px">Delete User Account?</h5>
                    <p class="text-muted small mb-4">Are you sure you want to delete
                        <span id="delete_user_name" class="fw-bold text-dark"></span>?
                        This action cannot be undone.
                    </p>

                    <form id="deleteUserForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="delete_user_id" name="user_id">
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
        document.addEventListener("DOMContentLoaded", function() {
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

            // Auto-open add modal if add form had validation errors
            @if ($errors->any() && old('user_id') === null)
                const addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
                addUserModal.show();
            @endif

            // Auto-open edit modal if edit form had validation errors
            @if ($errors->any() && old('user_id') !== null)
                const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                document.getElementById('edit_user_id').value = "{{ old('user_id') }}";
                document.getElementById('edit_fullname').value = "{{ old('name') }}";
                document.getElementById('edit_email').value = "{{ old('email') }}";
                document.getElementById('edit_role').value = "{{ old('is_admin') }}";
                document.getElementById('editUserForm').action = `/users/{{ old('user_id') }}`;
                editUserModal.show();
            @endif

            // Edit User - Populate modal when clicking edit buttons
            document.querySelectorAll('.edit-user-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const email = this.getAttribute('data-email');
                    const role = this.getAttribute('data-role');

                    document.getElementById('edit_user_id').value = id;
                    document.getElementById('edit_fullname').value = name;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_role').value = role === 'admin' ? '1' : '0';

                    // Update form action
                    document.getElementById('editUserForm').action = `/users/${id}`;
                });
            });

            // Delete User - Populate modal
            document.querySelectorAll('.delete-user-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    document.getElementById('delete_user_id').value = id;
                    document.getElementById('delete_user_name').textContent = name;
                    document.getElementById('deleteUserForm').action = `/users/${id}`;
                });
            });
        });
    </script>
</body>

</html>