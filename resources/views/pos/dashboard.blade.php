<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Name - Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
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
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('users.index') }}">
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
            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-1 main-container">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                    <div>
                        <h1 class="h3 mb-0 fw-semibold">Dashboard</h1>
                        <small class="text-muted" id="welcomeText">Welcome back, {{ Auth::user()->name }}!</small>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="text-end">
                            <div class="fw-medium text-dark" id="userTitle" style="font-size: 16px">
                               name : {{ Auth::user()->name }}
                            </div>
                            Role : <small class="text-muted" id="userRoleBadge" style="font-size: 12px">
                                {{ Auth::user()->isAdmin() ? 'Administrator' : 'Sales Person' }}
                            </small>
                        </div>

                        {{-- <img src="{{ Auth::user()->profile_photo_url ?? asset('img/user1.jpg') }}" alt="Profile"
                            class="rounded-circle border shadow-sm" style="width: 56px; height: 56px; object-fit: cover"
                            id="userimg"> --}}
                    </div>
                </div>

                <!-- Stats Cards (For Today) -->
                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6 col-lg-3 col-xl">
                        <div class="card dashboard-card p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-medium small">Today's Sales</span>
                                <div class="dashboard-card-icon bg-light-blue">
                                    <i class="bi bi-currency-dollar"></i>
                                </div>
                            </div>
                            <h4 class="mb-1 fw-bold">Ks. {{ number_format($todaySales ?? 0) }}</h4>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 col-xl">
                        <div class="card dashboard-card p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-medium small">Today's Cost</span>
                                <div class="dashboard-card-icon bg-light-blue">
                                    <i class="bi bi-cash"></i>
                                </div>
                            </div>
                            <h4 class="mb-1 fw-bold">Ks. {{ number_format($todayCosts ?? 0) }}</h4>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 col-xl">
                        <div class="card dashboard-card p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-medium small">Today's Profit</span>
                                <div class="dashboard-card-icon bg-light-green">
                                    <i class="bi bi-graph-up-arrow"></i>
                                </div>
                            </div>
                            <h4 class="mb-1 fw-bold text-success">Ks. {{ number_format($todayProfit ?? 0) }}</h4>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 col-xl">
                        <div class="card dashboard-card p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-medium small">Today's Orders</span>
                                <div class="dashboard-card-icon bg-light-orange">
                                    <i class="bi bi-cart3"></i>
                                </div>
                            </div>
                            <h4 class="mb-1 fw-bold">{{ $todayOrders ?? 0 }}</h4>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-lg-3 col-xl">
                        <div class="card dashboard-card p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fw-medium small">Total Products</span>
                                <div class="dashboard-card-icon bg-light-purple">
                                    <i class="bi bi-box"></i>
                                </div>
                            </div>
                            <h4 class="mb-1 fw-bold">{{ $totalProducts ?? 0 }}</h4>
                        </div>
                    </div>
                </div>

                <!-- Charts and Recent Sales -->
                <div class="row g-4">
                    <div class="col-12 col-xl-7">
                        <div class="card border-0 shadow-sm p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h6 class="fw-semibold mb-0">Sales Overview</h6>
                                <select class="form-select form-select-sm w-auto" id="timeFilter">
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                </select>
                            </div>
                            <div class="d-flex align-items-center justify-content-center bg-light rounded"
                                style="height: 280px">
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-5">
                        <div class="card border-0 shadow-sm p-4 h-100">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-semibold mb-0">Recent Sales</h6>
                                <a href="{{ route('reports.index') }}"
                                    class="btn btn-sm btn-link text-decoration-none">View All</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="font-size: 14px">
                                    <tbody id="recentSalesTable">
                                        @forelse($recentSales ?? [] as $sale)
                                            <tr>
                                                <td>
                                                    <span class="fw-medium d-block">{{ $sale->invoice_number }}</span>
                                                    <small
                                                        class="text-muted">{{ $sale->sale_date->format('d-M-Y h:i A') }}</small>
                                                </td>
                                                <td class="fw-semibold text-end">Ks.
                                                    {{ number_format($sale->total_amount, 0) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center text-muted small py-3">No recent
                                                    sales</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            // Get user role from Laravel
            const isAdmin = {{ Auth::user()->isAdmin() ? 'true' : 'false' }};
            const userRole = isAdmin ? 'admin' : 'seller';

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

            // Initial Chart Data
            const initialData = @json($chartData);

            // Initialize Chart
            const ctx = document.getElementById("salesChart").getContext("2d");
            const salesChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: initialData.labels,
                    datasets: [{
                        label: "Sales (Ks.)",
                        data: initialData.sales,
                        borderColor: "#3b82f6",
                        backgroundColor: "rgba(59, 130, 246, 0.1)",
                        tension: 0.4,
                        fill: true,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                },
            });

            // Time filter change event
            document.getElementById("timeFilter").addEventListener("change", function(e) {
                const filter = e.target.value;
                fetch(`{{ route('dashboard') }}?chart_period=${filter}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        salesChart.data.labels = data.labels;
                        salesChart.data.datasets[0].data = data.sales;
                        salesChart.update();
                    });
            });
        });
    </script>

</body>

</html>
