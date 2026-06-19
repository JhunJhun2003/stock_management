<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Name- Reports</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('css/report.css') }}" rel="stylesheet">
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
                            <a class="nav-link active" href="{{ route('reports.index') }}">
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
            <div class="col-md-10 px-4 py-4 main-container">
                <h4 class="fw-bold mb-3">Reports</h4>

                <!-- Filter Card -->
                <div class="card border-0 shadow-sm p-3 mb-4">
                    <form method="GET" action="{{ route('reports.index') }}" id="reportForm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label small text-muted">Report Type</label>
                                <select class="form-select form-select-sm" name="report_type" id="reportType">
                                    <option value="sales" {{ request('report_type') == 'sales' ? 'selected' : '' }}>
                                        Sales Report</option>
                                    <option value="products"
                                        {{ request('report_type') == 'products' ? 'selected' : '' }}>Product Report
                                    </option>
                                    <option value="users" {{ request('report_type') == 'users' ? 'selected' : '' }}>
                                        User Report</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small text-muted">From Date</label>
                                <input type="date" class="form-control form-control-sm" name="from_date"
                                    value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label small text-muted">To Date</label>
                                <input type="date" class="form-control form-control-sm" name="to_date"
                                    value="{{ request('to_date', now()->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-2" id="sellerFilter">
                                <label class="form-label small text-muted">Seller</label>
                                <select class="form-select form-select-sm" name="seller_id">
                                    <option value="">All Sellers</option>
                                    @foreach($sellers ?? [] as $seller)
                                        <option value="{{ $seller->id }}" 
                                            {{ request('seller_id') == $seller->id ? 'selected' : '' }}>
                                            {{ $seller->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2" id="invoiceFilter">
                                <label class="form-label small text-muted">Invoice No.</label>
                                <input type="text" class="form-control form-control-sm" name="invoice_number" 
                                    placeholder="Search Invoice" value="{{ request('invoice_number') }}">
                            </div>

                            <div class="col-md-2 d-flex gap-1">
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    <i class="bi bi-search"></i> Generate
                                </button>
                                <button type="button" class="btn btn-success btn-sm w-100" onclick="exportReport()">
                                    <i class="bi bi-file-earmark-excel"></i> Export
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-3 text-center mb-4">
                    <div class="col-md-3">
                        <div class="stat-card p-3 border rounded bg-white shadow-sm">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary mb-2">
                                <i class="bi bi-currency-dollar fs-4"></i>
                            </div>
                            <div class="stat-value fw-bold">Ks. {{ number_format($totalSales ?? 0) }}</div>
                            <div class="stat-label text-muted small">Total Sales</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 border rounded bg-white shadow-sm">
                            <div class="stat-icon bg-danger bg-opacity-10 text-danger mb-2">
                                <i class="bi bi-cash-stack fs-4"></i>
                            </div>
                            <div class="stat-value fw-bold">Ks. {{ number_format($totalCost ?? 0) }}</div>
                            <div class="stat-label text-muted small">Total Cost</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 border rounded bg-white shadow-sm">
                            <div class="stat-icon bg-success bg-opacity-10 text-success mb-2">
                                <i class="bi bi-graph-up-arrow fs-4"></i>
                            </div>
                            <div class="stat-value fw-bold text-success">Ks. {{ number_format($netProfit ?? 0) }}</div>
                            <div class="stat-label text-muted small">Net Profit</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 border rounded bg-white shadow-sm">
                            <div class="stat-icon bg-info bg-opacity-10 text-info mb-2">
                                <i class="bi bi-calculator fs-4"></i>
                            </div>
                            <div class="stat-value fw-bold">Ks. {{ number_format($averageSale ?? 0) }}</div>
                            <div class="stat-label text-muted small">Average Sale</div>
                        </div>
                    </div>
                </div>

                <!-- Report Table -->
                <div class="table-container p-3 bg-white border rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="fw-bold mb-0">
                                {{ $reportTitle ?? 'Sales Report Details' }}
                                @if(request('seller_id') && $reportType === 'sales')
                                    <span class="badge bg-primary ms-2">
                                        Filtered by Seller
                                    </span>
                                @endif
                            </h6>
                        </div>
                        <span class="text-muted small">
                            @if($reportType === 'sales')
                                {{ $sales->total() ?? 0 }} records found
                            @else
                                {{ count($reportData) }} records found
                            @endif
                        </span>
                    </div>

                    <div class="table-responsive">
                        @if($reportType === 'sales')
                            <!-- Sales Report Table -->
                            <table class="table table-hover align-middle mb-0" style="font-size: 14px">
                                <thead class="table-light">
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Seller</th>
                                        <th>Items</th>
                                        <th>Subtotal</th>
                                        <th>Discount</th>
                                        <th>Sales Amount</th>
                                        <th>Cost Amount</th>
                                        <th>Net Profit</th>
                                        <th>Payment</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sales ?? [] as $sale)
                                        @php
                                            $saleCost = 0;
                                            foreach($sale->saleDetails as $detail) {
                                                $saleCost += $detail->quantity * ($detail->product->cost ?? 0);
                                            }
                                            $saleProfit = $sale->total_amount - $saleCost;
                                        @endphp
                                        <tr>
                                            <td><span class="fw-bold">{{ $sale->invoice_number }}</span></td>
                                            <td>{{ $sale->sale_date->format('Y-m-d H:i') }}</td>
                                            <td>{{ $sale->customer_name ?? 'Walk-in' }}</td>
                                            <td>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                    {{ $sale->user->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ $sale->saleDetails->sum('quantity') ?? 0 }}</td>
                                            <td>Ks. {{ number_format($sale->total_amount + $sale->discount, 0) }}</td>
                                            <td class="text-danger">Ks. {{ number_format($sale->discount, 0) }}</td>
                                            <td class="fw-semibold">Ks. {{ number_format($sale->total_amount, 0) }}</td>
                                            <td class="text-danger fw-semibold">Ks. {{ number_format($saleCost, 0) }}</td>
                                            <td class="text-success fw-semibold">Ks. {{ number_format($saleProfit, 0) }}</td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-10 text-info">
                                                    {{ ucfirst($sale->payment_method ?? 'cash') }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewReceipt({{ $sale->id }})">
                                                    <i class="bi bi-receipt me-1"></i> Receipt
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-4">
                                                <i class="bi bi-inbox fs-1 d-block text-muted"></i>
                                                <p class="text-muted mt-2">No sales data available for the selected period</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">Total:</td>
                                        <td class="fw-bold">Ks. {{ number_format($sales->sum('total_amount') + $sales->sum('discount'), 0) }}</td>
                                        <td class="fw-bold text-danger">Ks. {{ number_format($sales->sum('discount'), 0) }}</td>
                                        <td class="fw-bold">Ks. {{ number_format($totalSales ?? 0, 0) }}</td>
                                        <td class="fw-bold text-danger">Ks. {{ number_format($totalCost ?? 0, 0) }}</td>
                                        <td class="fw-bold text-success">Ks. {{ number_format($netProfit ?? 0, 0) }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>

                        @elseif($reportType === 'products')
                            <!-- Product Report Table -->
                            <table class="table table-hover align-middle mb-0" style="font-size: 14px">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Product code</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Cost</th>
                                        <th>Total Sold</th>
                                        <th>Total Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportData ?? [] as $product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $product->product_code }}</td>
                                            <td>{{ $product->product_name }}</td>
                                            <td>Ks. {{ number_format($product->price, 0) }}</td>
                                            <td>Ks. {{ number_format($product->cost, 0) }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $product->total_sold ?? 0 }}</span>
                                            </td>
                                            <td class="fw-semibold text-success">
                                                Ks. {{ number_format($product->total_revenue ?? 0, 0) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="bi bi-inbox fs-1 d-block text-muted"></i>
                                                <p class="text-muted mt-2">No product data available</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        @elseif($reportType === 'users')
                            <!-- User Report Table -->
                            <table class="table table-hover align-middle mb-0" style="font-size: 14px">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Staff Name</th>
                                        <th>Total Sales</th>
                                        <th>Total Revenue</th>
                                        <th>Avg. Sale</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportData ?? [] as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->total_sales ?? 0 }}</td>
                                            <td class="fw-semibold text-success">
                                                Ks. {{ number_format($user->total_revenue ?? 0, 0) }}
                                            </td>
                                            <td>
                                                Ks. {{ number_format(($user->total_sales > 0 ? $user->total_revenue / $user->total_sales : 0), 0) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="bi bi-inbox fs-1 d-block text-muted"></i>
                                                <p class="text-muted mt-2">No user data available</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif
                    </div>

                    <!-- Pagination -->
                    @if($reportType === 'sales' && isset($sales) && method_exists($sales, 'links'))
                        <div class="mt-3">
                            {{ $sales->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt View Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
          <div class="modal-header bg-light border-0 py-3">
            <h6 class="modal-title fw-bold text-dark">
              <i class="bi bi-receipt text-primary me-2"></i>Invoice Details
            </h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-4 pt-2">
            <!-- Receipt Slip Container -->
            <div id="receiptSlipArea" style="font-family: monospace;">
              <div class="text-center">
                <h5 class="fw-bold mb-0">POS Name</h5>
                <p class="small mb-1"> No.123, Yangon <br> Ph: 09-123456789 </p>
                <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
              </div>

              <div class="small my-2" style="font-size: 12px;">
                <div><strong>Date:</strong> <span id="recDate">-</span></div>
                <div><strong>Invoice:</strong> <span id="recInvoice">-</span></div>
                <div><strong>Customer:</strong> <span id="recCustomer">-</span></div>
                <div><strong>Cashier:</strong> <span id="recCashier">-</span></div>
              </div>

              <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>

              <table class="w-100 small my-2" style="font-size: 12px;">
                <thead>
                  <tr>
                    <th class="text-start">Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Amount</th>
                  </tr>
                </thead>
                <tbody id="recItems"></tbody>
              </table>

              <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>

              <div class="d-flex justify-content-between small my-1" style="font-size: 12px;">
                <span>Subtotal:</span><span id="recSubtotal">Ks. 0</span>
              </div>
              <div class="d-flex justify-content-between small my-1" style="font-size: 12px;">
                <span>Discount:</span><span id="recDiscount">Ks. 0</span>
              </div>
              <div class="d-flex justify-content-between fw-bold my-1" style="font-size: 13px;">
                <span>TOTAL:</span><span id="recTotal">Ks. 0</span>
              </div>

              <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>

              <div class="small my-1" style="font-size: 12px;">
                <div><strong>Pay Method:</strong> <span id="recMethod">Cash</span></div>
                <div id="recReceivedLine"><strong>Received:</strong> <span id="recReceived">Ks. 0</span></div>
                <div id="recChangeLine"><strong>Change:</strong> <span id="recChange">Ks. 0</span></div>
              </div>
              
              <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>

              <div class="text-center small mt-3" style="font-size: 11px;">
                <p class="mb-0">Thank You! Please Come Again</p>
                <small style="font-size: 9px">Powered by My POS System</small>
              </div>
            </div>
            
            <div class="mt-4 d-flex gap-2">
                <button type="button" class="btn btn-light btn-sm w-100 py-2" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm w-100 py-2 fw-bold" onclick="printReceiptSlip()">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>
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

            // Show/hide filters based on report type
            const reportType = document.getElementById('reportType');
            const sellerFilter = document.getElementById('sellerFilter');
            const invoiceFilter = document.getElementById('invoiceFilter');
            
            function toggleSellerFilter() {
                if (reportType.value === 'sales') {
                    sellerFilter.style.display = 'block';
                    invoiceFilter.style.display = 'block';
                } else {
                    sellerFilter.style.display = 'none';
                    invoiceFilter.style.display = 'none';
                }
            }
            
            toggleSellerFilter();
            reportType.addEventListener('change', toggleSellerFilter);
        });

        // Export function
        function exportReport() {
            const form = document.getElementById('reportForm');
            
            const exportForm = document.createElement('form');
            exportForm.method = 'POST';
            exportForm.action = '{{ route('reports.export') }}';

            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            exportForm.appendChild(csrfInput);

            // Copy all form data
            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                exportForm.appendChild(input);
            }

            document.body.appendChild(exportForm);
            exportForm.submit();
            document.body.removeChild(exportForm);
        }

        function formatMoney(amount) {
          return 'Ks. ' + Number(amount).toLocaleString();
        }

        async function viewReceipt(id) {
            try {
                const response = await fetch(`/sales/${id}`);
                if (!response.ok) {
                    alert('Failed to fetch receipt details.');
                    return;
                }
                const sale = await response.json();
                
                document.getElementById('recDate').textContent = sale.sale_date;
                document.getElementById('recInvoice').textContent = sale.invoice_number;
                document.getElementById('recCustomer').textContent = sale.customer_name || 'Walk-in';
                document.getElementById('recCashier').textContent = sale.cashier;
                
                const recItems = document.getElementById('recItems');
                recItems.innerHTML = sale.items.map(item => `
                    <tr>
                      <td>${item.name}</td>
                      <td class="text-center">${item.quantity}</td>
                      <td class="text-end">${formatMoney(item.subtotal)}</td>
                    </tr>
                `).join('');

                const subtotal = sale.items.reduce((sum, item) => sum + item.subtotal, 0);
                const discount = sale.discount || 0;
                
                document.getElementById('recSubtotal').textContent = formatMoney(subtotal);
                document.getElementById('recDiscount').textContent = formatMoney(discount);
                document.getElementById('recTotal').textContent = formatMoney(sale.total_amount);
                
                document.getElementById('recMethod').textContent = sale.payment_method.toUpperCase();
                
                const recReceivedLine = document.getElementById('recReceivedLine');
                const recChangeLine = document.getElementById('recChangeLine');
                
                if (sale.payment_method === 'cash') {
                    recReceivedLine.style.display = 'block';
                    recChangeLine.style.display = 'block';
                    document.getElementById('recReceived').textContent = formatMoney(sale.payment_amount);
                    document.getElementById('recChange').textContent = formatMoney(sale.change_amount);
                } else {
                    recReceivedLine.style.display = 'none';
                    recChangeLine.style.display = 'none';
                }

                const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
                modal.show();
            } catch(e) {
                alert('Something went wrong.');
            }
        }

        function printReceiptSlip() {
            const printContent = document.getElementById('receiptSlipArea').innerHTML;
            const printWindow = window.open('', '_blank', 'width=400,height=600');
            printWindow.document.write('<html><head><title>Print Receipt</title>');
            printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
            printWindow.document.write('<style>body { padding: 20px; font-family: monospace; }</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(printContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            setTimeout(function() {
                printWindow.print();
                printWindow.close();
            }, 250);
        }
    </script>
</body>

</html>
