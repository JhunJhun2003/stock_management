<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>သီတာပြုံး  - အသုံးပြုသူများ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="icon" type="image" href="{{ asset('img/logo.jpg') }}">
    <link href="{{ asset('css/user_management.css') }}" rel="stylesheet">

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex flex-column align-items-center justify-content-center mt-3">
                        <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="rounded-circle"
                            style="width: 56px; height: 56px; object-fit: cover;">
                        
                        <h5 class="fw-bold text-white p-3 mb-2" style="border-bottom: 1px solid #1e293b; width: 100%; text-align: center;">
                            သီတာပြုံး
                        </h5>
                    </div>

                    <ul class="nav flex-column gap-1">
                        <!-- Admin Menu Items -->
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> ပင်မစာမျက်မှာ
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link  text-white {{ request()->routeIs('users.index') ? 'activeRoute' : '' }}" 
                                href="{{ route('users.index') }}">
                                <i class="bi bi-people"></i> အသုံးပြုသူများ
                            </a>
                        </li>

                        

                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('products.index') }}">
                                <i class="bi bi-box-seam"></i> ကုန်ပစ္စည်းများ
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('reports.index') }}">
                                <i class="bi bi-graph-up"></i> အစီရင်ခံစာများ
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('settings.index') }}">
                                <i class="bi bi-gear"></i> ဆက်တင်များ
                            </a>
                        </li>

                        <!-- Seller Menu Items -->
                        <li class="nav-item seller-only {{ !Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('pos.index') }}">
                                <i class="bi bi-cpu"></i> အရောင်း
                            </a>
                        </li>
                        <li class="nav-item seller-only {{ !Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('sales.history') }}">
                                <i class="bi bi-receipt"></i> ရောင်းချမှုမှတ်တမ်း
                            </a>
                        </li>
                        <li class="nav-item seller-only {{ !Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('settings.index') }}">
                                <i class="bi bi-gear"></i> ဆက်တင်များ
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
                                    <i class="bi bi-box-arrow-right"></i> အကောင့်ထွက်ရန်
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
                        <h4 class="fw-bold mb-1">အသုံးပြုသူများ</h4>
                        <small class="text-muted">အသုံးပြုသူများ၏ အကောင့်များကို စီမံခန့်ခွဲရန်။</small>
                    </div>

                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-plus-lg"></i> အသုံးပြုသူအသစ်ထည့်ရန်
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
                                    <th>အမည်အပြည့်အစုံ</th>
                                    <th>အီးမေးလ်</th>
                                    <th class="text-center">ရာထူး</th>
                                    <th class="text-center">လုပ်ဆောင်ချက်များ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td class="text-center">
                                            @if ($user->isAdmin())
                                                <span class="badge bg-primary">စီမံခန့်ခွဲသူ</span>
                                            @else
                                                <span class="badge bg-info">အရောင်းဝန်ထမ်း</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
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
                                            <p class="text-muted mt-2">အသုံးပြုသူ မတွေ့ရှိပါ။</p>
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
                    <h5 class="modal-title fw-bold" style="font-size: 18px">အသုံးပြုသူအကောင့်အသစ် ထည့်သွင်းရန်</h5>
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
                                <label class="form-label small fw-medium text-muted">အမည် အပြည့်အစုံ *</label>
                                <input type="text" class="form-control" id="add_fullname" name="name"
                                    value="{{ old('name') }}"
                                    placeholder="နာမည်အပြည့်အစုံ ရိုက်ထည့်ပါ" required style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">အီးမေးလ် *</label>
                                <input type="email" class="form-control" id="add_email" name="email"
                                    value="{{ old('email') }}"
                                    placeholder="အီးမေးလ် ရိုက်ထည့်ပါ" required style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">စကားဝှက်ကို *</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="add_password" name="password"
                                        placeholder="စကားဝှက် ရိုက်ထည့်ပါ (အနည်းဆုံး ၈ လုံး)" required style="font-size: 16px"
                                        autocomplete="new-password" autocapitalize="none" spellcheck="false">
                                    <button class="btn btn-outline-secondary" type="button" id="toggle_add_password" aria-label="Show password">
                                        <i class="bi bi-eye" id="toggle_add_password_icon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">ရာထူး *</label>
                                <select class="form-select" id="add_role" name="is_admin" style="font-size: 16px" required>
                                    <option value="" selected disabled>ရာထူး ရွေးချယ်ပါ</option>
                                    <option value="1" {{ old('is_admin') === '1' ? 'selected' : '' }}>စီမံခန့်ခွဲသူ</option>
                                    <option value="0" {{ old('is_admin') === '0' ? 'selected' : '' }}>အရောင်းဝန်ထမ်း</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-top-0">
                        <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal"> မလုပ်တေ့ာပါ။ </button>
                        <button type="submit" class="btn btn-primary btn-sm px-3">သိမ်းဆည်းမည်။</button>
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
                    <h5 class="modal-title fw-bold" style="font-size: 18px">အသုံးပြုသူအချက်အလက် ပြင်ဆင်ရန်<</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body bordered p-4">
                        <input type="hidden" id="edit_user_id" name="user_id">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">အမည် အပြည့်အစုံ *</label>
                                <input type="text" class="form-control" id="edit_fullname" name="name"
                                    required style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">အီးမေးလ် *</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required
                                    style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">စကားဝှက်အသစ်(ပြင်ဆင်လိုပါက)</label>
                                <input type="password" class="form-control" id="edit_password" name="password"
                                    placeholder="ယခင်အတိုင်း ထားချင်လျှင် ကွက်လပ်ချန်ထားပါ" style="font-size: 16px">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">ရာထူး *</label>
                                <select class="form-select" id="edit_role" name="is_admin" style="font-size: 16px" required>
                                    <option value="1">စီမံခန့်ခွဲသူ</option>
                                    <option value="0">အရောင်းဝန်ထမ်း</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-top-0">
                        <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">မလုပ်တေ့ာပါ။</button>
                        <button type="submit" class="btn btn-primary btn-sm px-3">ပြင်ဆင်ချက် သိမ်းဆည်းမည်။</button>
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
                    <h5 class="fw-bold mb-2" style="font-size: 16px">အသုံးပြုသူအကောင့်ကို ဖျက်သိမ်းမလား?</h5>
                    <p class="text-muted small mb-4">ကို ဖျက်ပစ်ရန် သေချာပါသလား?
                        <span id="delete_user_name" class="fw-bold text-dark"></span>?
                        ဤလုပ်ဆောင်ချက်ကို ပြန်ပြင်၍ ရမည်မဟုတ်ပါ။
                    </p>

                    <form id="deleteUserForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="delete_user_id" name="user_id">
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">မလုပ်တေ့ာပါ။</button>
                            <button type="submit" class="btn btn-danger btn-sm px-3">ဖျက်မည်။</button>
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

            const toggleAddPasswordButton = document.getElementById('toggle_add_password');
            if (toggleAddPasswordButton) {
                toggleAddPasswordButton.addEventListener('click', function () {
                    const passwordInput = document.getElementById('add_password');
                    const icon = document.getElementById('toggle_add_password_icon');
                    const isPassword = passwordInput.type === 'password';

                    passwordInput.type = isPassword ? 'text' : 'password';
                    icon.className = isPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
                });
            }
        });
    </script>
</body>

</html>