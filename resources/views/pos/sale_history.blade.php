<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Name- Sales History</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('css/sale_history.css') }}" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- ===== SIDEBAR ===== -->
            <div class="col-md-2 sidebar d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="sidebar-brand d-flex align-items-center justify-content-center gap-2">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo"
                            style="width: 32px; height: auto; object-fit: contain;">
                        <h5 class="fw-bold text-white m-0">POS Name</h5>
                    </div>

                    <ul class="nav flex-column gap-1">
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('users.index') }}">
                                <i class="bi bi-people"></i> User Management
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('products.index') }}">
                                <i class="bi bi-box-seam"></i> Product Management
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('reports.index') }}">
                                <i class="bi bi-graph-up"></i> Reports
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('settings.index') }}">
                                <i class="bi bi-gear"></i> Settings
                            </a>
                        </li>

                        <li class="nav-item seller-only {{ Auth::user()->isSeller() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('seller.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item seller-only {{ Auth::user()->isSeller() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('pos.index') }}">
                                <i class="bi bi-cpu"></i> POS (Sales)
                            </a>
                        </li>
                        <li class="nav-item seller-only {{ Auth::user()->isSeller() ? 'show' : '' }}">
                            <a class="nav-link text-white {{ request()->routeIs('sales.history') ? 'active' : '' }}"
                                href="{{ route('sales.history') }}">
                                <i class="bi bi-receipt"></i> Sales History
                            </a>
                        </li>
                        <li class="nav-item seller-only {{ Auth::user()->isSeller() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('settings.index') }}">
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
            <div class="col-md-10 px-4 py-4 main-content">
                <h4 class="fw-bold mb-3">Sales History</h4>

                <!-- Filter Card -->
                <div class="card border-0 shadow-sm p-3 mb-4">
                    <form method="GET" action="{{ route('sales.history') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small text-muted">From Date</label>
                                <input type="date" class="form-control form-control-sm" name="from_date" value="{{ request('from_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small text-muted">To Date</label>
                                <input type="date" class="form-control form-control-sm" name="to_date" value="{{ request('to_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small text-muted">Search Invoice</label>
                                <input type="text" class="form-control form-control-sm" name="search" placeholder="Search Invoice No." value="{{ request('search') }}">
                            </div>

                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Search</button>
                                @if(request('from_date') || request('to_date') || request('search'))
                                    <a href="{{ route('sales.history') }}" class="btn btn-secondary btn-sm w-100">Clear</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Sales Table -->
                <div class="card border-0 shadow-sm p-3">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 16px">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice No.</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Subtotal</th>
                                    <th>Discount</th>
                                    <th>Total Amount</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($sales as $sale)
                                    <tr>
                                        <td><span class="fw-bold">{{ $sale->invoice_number }}</span></td>
                                        <td>{{ $sale->sale_date->format('d/m/Y h:i A') }}</td>
                                        <td>{{ $sale->customer_name ?? 'Walk-in' }}</td>
                                        <td>{{ $sale->saleDetails->sum('quantity') }}</td>
                                        <td>Ks. {{ number_format($sale->total_amount + $sale->discount, 0) }}</td>
                                        <td class="text-danger">Ks. {{ number_format($sale->discount, 0) }}</td>
                                        <td class="fw-semibold">Ks. {{ number_format($sale->total_amount, 0) }}</td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                {{ ucfirst($sale->payment_method) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success">Completed</span>
                                        </td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewReceipt({{ $sale->id }})">
                                                <i class="bi bi-receipt me-1"></i> View Receipt
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-1 d-block text-muted"></i>
                                            <p class="mt-2">No sales matching your criteria</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($sales, 'links'))
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

    <!-- ===== SCRIPTS ===== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            const isAdmin = {{ Auth::user()->isAdmin() ? 'true' : 'false' }};

            if (isAdmin) {
                document.querySelectorAll(".seller-only").forEach(el => el.style.setProperty("display", "none",
                    "important"));
                document.querySelectorAll(".admin-only").forEach(el => el.style.setProperty("display", "block",
                    "important"));
            } else {
                document.querySelectorAll(".admin-only").forEach(el => el.style.setProperty("display", "none",
                    "important"));
                document.querySelectorAll(".seller-only").forEach(el => el.style.setProperty("display", "block",
                    "important"));
            }
        });

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
            printWindow.document.write('<style>body { padding: 4mm; margin: 0; font-family: "Courier New", Courier, monospace; font-size: 12px; width: 80mm; max-width: 80mm; box-sizing: border-box; } @page { size: 80mm auto; margin: 0mm; }</style>');
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
