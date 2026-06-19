<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POS Name - Seller Dashboard</title>

  <!-- External CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Dashboard CSS -->
  <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <!-- ===== SIDEBAR ===== -->
      <div class="col-md-2 sidebar d-flex flex-column justify-content-between p-3">
        <div>
          <div class="sidebar-brand d-flex align-items-center justify-content-center gap-2">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" style="width: 32px; height: auto; object-fit: contain;">
            <h5 class="fw-bold text-white m-0">POS Name</h5>
          </div>

          <ul class="nav flex-column gap-1">
            <!-- Seller Menu Items -->
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}"
                href="{{ route('seller.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('pos.index') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                <i class="bi bi-cpu"></i> POS (Sales)
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('sales.history') ? 'active' : '' }}"
                href="{{ route('sales.history') }}">
                <i class="bi bi-receipt"></i> Sales History
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('settings.index') ? 'active' : '' }}"
                href="{{ route('settings.index') }}">
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

      <!-- ===== MAIN CONTENT ===== -->
      <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-1 main-container">
        <!-- Header -->
        <div
          class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
          <div>
            <h1 class="h3 mb-0 fw-semibold">Seller Dashboard</h1>
            <small class="text-muted" id="welcomeText">Welcome back, {{ Auth::user()->name }}!</small>
          </div>

          <div class="d-flex align-items-center gap-3">
            <div class="text-end">
              <div class="fw-medium text-dark" id="userTitle" style="font-size: 16px">
                Name : {{ Auth::user()->name }}
              </div>
              <small class="text-muted" id="userRoleBadge" style="font-size: 12px">
                Role : Sales Person
              </small>
            </div>

            {{-- <img src="{{ Auth::user()->profile_photo_url ?? asset('img/user2.jpg') }}" alt="Profile"
              class="rounded-circle border shadow-sm" style="width: 56px; height: 56px; object-fit: cover" id="userimg"> --}}
          </div>
        </div>

        <!-- Stats Cards (For Today) -->
        <div class="row g-3 mb-4">
          <div class="col-12 col-sm-6 col-xl-4">
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

          <div class="col-12 col-sm-6 col-xl-4">
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
        </div>

        <!-- Charts and Recent Sales -->
        <div class="row g-4">
          <div class="col-12 col-xl-7">
            <div class="card border-0 shadow-sm p-4 h-100">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h6 class="fw-semibold mb-0">Sales Overview</h6>
                <select class="form-select form-select-sm w-auto" id="timeFilter">
                  <option value="today">Today</option>
                  <option value="week">This Week</option>
                  <option value="month">This Month</option>
                </select>
              </div>
              <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height: 280px">
                <canvas id="salesChart"></canvas>
              </div>
            </div>
          </div>

          <div class="col-12 col-xl-5">
            <div class="card border-0 shadow-sm p-4 h-100">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-semibold mb-0">Recent Sales</h6>
                <a href="{{ route('sales.history') }}" class="btn btn-sm btn-link text-decoration-none">View All</a>
              </div>
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 14px">
                  <tbody id="recentSalesTable">
                    @forelse($recentSales ?? [] as $sale)
                        <tr>
                            <td>
                                <span class="fw-medium d-block">{{ $sale->invoice_number }}</span>
                                <small class="text-muted">{{ $sale->sale_date->format('d-M-Y h:i A') }}</small>
                            </td>
                            <td class="fw-semibold text-end">Ks. {{ number_format($sale->total_amount, 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted small py-3">No recent sales</td>
                        </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
          <div class="col-12">
            <div class="card border-0 shadow-sm p-4">
              <h6 class="fw-semibold mb-3">Quick Actions</h6>
              <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('pos.index') }}" class="btn btn-primary">
                  <i class="bi bi-cpu me-2"></i> Start New Sale
                </a>
                <a href="{{ route('sales.history') }}" class="btn btn-outline-primary">
                  <i class="bi bi-receipt me-2"></i> View History
                </a>
                <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary">
                  <i class="bi bi-gear me-2"></i> Settings
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== SCRIPTS ===== -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function () {
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

      // Initial Chart Data from backend
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
          plugins: { legend: { display: false } },
          scales: {
            y: { beginAtZero: true },
            x: { grid: { display: false } }
          },
        },
      });

      // Time filter change event
      document.getElementById("timeFilter").addEventListener("change", function (e) {
        const filter = e.target.value;
        fetch(`{{ route('seller.dashboard') }}?chart_period=${filter}`, {
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