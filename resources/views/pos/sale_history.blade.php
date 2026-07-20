<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>မသီတာပြုံး - ရောင်းချမှုမှတ်တမ်း</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image" href="{{ asset('img/logo.jpg') }}">
    <link href="{{ asset('css/sale_history.css') }}" rel="stylesheet">

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- ===== SIDEBAR ===== -->
            <div class="col-md-2 sidebar d-flex flex-column justify-content-between p-3">
                <div>
                    <div class="d-flex flex-column align-items-center justify-content-center mt-3">
                        <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="rounded-circle"
                            style="width: 56px; height: 56px; object-fit: cover;">

                        <h5 class="fw-bold text-white p-3 mb-2"
                            style="border-bottom: 1px solid #1e293b; width: 100%; text-align: center;">
                            မသီတာပြုံး
                        </h5>
                    </div>

                    <ul class="nav flex-column gap-1">
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> ပင်မစာမျက်နှာ
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('users.index') }}">
                                <i class="bi bi-people"></i> အသုံးပြုသူများ
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('products.index') }}">
                                <i class="bi bi-box-seam"></i> ကုန်ပစ္စည်းများ
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('reports.index') }}">
                                <i class="bi bi-graph-up"></i> အစီရင်ခံစာများ
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('settings.index') }}">
                                <i class="bi bi-gear"></i> ဆက်တင်များ
                            </a>
                        </li>

                        <li class="nav-item seller-only {{ Auth::user()->isSeller() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('seller.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> ပင်မစာမျက်နှာ
                            </a>
                        </li>
                        <li class="nav-item seller-only {{ Auth::user()->isSeller() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('pos.index') }}">
                                <i class="bi bi-cpu"></i> အရောင်း
                            </a>
                        </li>
                        <li class="nav-item seller-only {{ Auth::user()->isSeller() ? 'show' : '' }}">
                            <a class="nav-link text-white {{ request()->routeIs('sales.history') ? 'activeRoute' : '' }}"
                                href="{{ route('sales.history') }}">
                                <i class="bi bi-receipt"></i> ရောင်းချမှု မှတ်တမ်း
                            </a>
                        </li>
                        <li class="nav-item seller-only {{ Auth::user()->isSeller() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('settings.index') }}">
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

            <!-- ===== MAIN CONTENT ===== -->
            <div class="col-md-10 px-4 py-4 main-content">
                <h4 class="fw-bold mb-3">ရောင်းချမှုမှတ်တမ်း</h4>

                <!-- Filter Card -->
                <div class="card border-0 shadow-sm p-3 mb-4">
                    <form method="GET" action="{{ route('sales.history') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small text-muted">စတင်သည့် ရက်စွဲ</label>
                                <input type="date" class="form-control form-control-sm" name="from_date"
                                    value="{{ request('from_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small text-muted">ပြီးဆုံးသည့် ရက်စွဲ</label>
                                <input type="date" class="form-control form-control-sm" name="to_date"
                                    value="{{ request('to_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small text-muted">ဘောင်ချာနံပါတ်ရှာရန်</label>
                                <input type="text" class="form-control form-control-sm" name="search"
                                    placeholder="ဘောင်ချာနံပါတ်ရှာရန်" value="{{ request('search') }}">
                            </div>

                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary btn-sm w-100">ရှာဖွေရန်</button>
                                @if (request('from_date') || request('to_date') || request('search'))
                                    <a href="{{ route('sales.history') }}"
                                        class="btn btn-secondary btn-sm w-100">မူလအတိုင်းပြန်လုပ်ရန်</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Sales Table -->
                <div class="card border-0 shadow-sm p-3">
                    <div class="table-responsive table-scroll" style="max-height: 70vh; overflow-y: auto;">
                        <table class="table table-hover align-middle text-nowrap text-center custom-table mb-0 p-5"
                            style="font-size: 14px; margin-bottom: 0;">
                             <thead style="position: sticky; top: 0; ">
                                <tr>
                                    <th>ဘောင်ချာနံပါတ်</th>
                                    <th>လုပ်ဆောင်ချက်</th>
                                    <th>ရက်စွဲ</th>
                                    <th>ဝယ်သူ</th>
                                    <th>ပစ္စည်းအရေအတွက်</th>
                                    <th>စုစုပေါင်း</th>
                                    <th>လျှော့ဈေး</th>
                                    <th>စုစုပေါင်းကျသင့်ငွေ</th>
                                    <th>ငွေပေးချေမှု</th>
                                    <th>အခြေအနေ</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($sales as $sale)
                                    <tr>
                                        <td><span class="fw-bold">{{ $sale->invoice_number }}</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary"
                                                onclick="viewReceipt({{ $sale->id }})">
                                                <i class="bi bi-receipt me-1"></i> ဘောင်ချာကြည့်ရန်
                                            </button>
                                        </td>
                                        <td>{{ $sale->sale_date->format('d/m/Y h:i A') }}</td>
                                        <td>{{ $sale->customer_name ?? 'ပုံမှန်ဝယ်ယူသူ' }}</td>
                                        <td>{{ $sale->saleDetails->sum('quantity') }}</td>
                                        <td> {{ number_format($sale->total_amount + $sale->discount, 0) }}</td>
                                        <td class="text-danger"> {{ number_format($sale->discount, 0) }}</td>
                                        <td class="fw-semibold"> {{ number_format($sale->total_amount, 0) }}</td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                {{ ucfirst($sale->payment_method) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success">အောင်မြင်ပါသည်။</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-1 d-block text-muted"></i>
                                            <p class="mt-2">ရှာဖွေမှုနှင့် ကိုက်ညီသော အရောင်းမှတ်တမ်းမရှိပါ။</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
                        <i class="bi bi-receipt text-primary me-2"></i>ဘောင်ချာအသေးစိတ်
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-2">
                    <!-- Receipt Slip Container -->
                    <div id="receiptSlipArea" style="font-family: 'Pyidaungsu', 'Noto Sans Myanmar', 'Myanmar Text', 'Zawgyi-One', monospace;">
                        <div class="text-center">
                            <h5 class="fw-bold mb-2">မသီတာပြုံး</h5>
                            <h6 class="fw-bold mb-2">မုန့်မျိုးစုံရောင်းဝယ်ရေး</h6>
                            <p class="small mb-2">
                                ရန်ပယ်စျေး၊ မကွေး <br>
                                095341934, 09965341934, 09782878443
                            </p>
                            <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>
                        </div>

                        <div class="small my-2" style="font-size: 12px;">
                            <div><strong>ရက်စွဲ။</strong> <span id="recDate">-</span></div>
                            <div><strong>ဘောင်ချာနံပါတ်</strong> <span id="recInvoice">-</span></div>
                            <div><strong>ဝယ်ယူသူ</strong> <span id="recCustomer">-</span></div>
                            <div><strong>အရောင်းဝန်ထမ်း</strong> <span id="recCashier">-</span></div>
                        </div>

                        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>

                        <table class="w-100 small my-2" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th class="text-start">ပစ္စည်း</th>
                                    <th class="text-center">အရေအတွက်</th>
                                    <th class="text-end">ဈေးနှုန်း</th>
                                </tr>
                            </thead>
                            <tbody id="recItems"></tbody>
                        </table>

                        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>

                        <div class="d-flex justify-content-between small my-1" style="font-size: 12px;">
                            <span>စုစုပေါင်း</span><span id="recSubtotal">၀ ကျပ်</span>
                        </div>
                        <div class="d-flex justify-content-between small my-1" style="font-size: 12px;">
                            <span>လျှော့ဈေး</span><span id="recDiscount">၀ ကျပ်</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold my-1" style="font-size: 13px;">
                            <span>စုစုပေါင်းကျသင့်ငွေ</span><span id="recTotal">၀ ကျပ်</span>
                        </div>

                        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>

                        <div class="small my-1" style="font-size: 12px;">
                            <div><strong>ငွေပေးချေမှု</strong> <span id="recMethod">ငွေသား</span></div>
                            <div id="recReceivedLine"><strong>လက်ခံရရှိငွေ</strong> <span id="recReceived">၀ ကျပ်</span></div>
                            <div id="recChangeLine"><strong>ပြန်အမ်းငွေ</strong> <span id="recChange">၀ ကျပ်</span></div>
                        </div>

                        <div style="border-top: 1px dashed #000; margin: 10px 0;"></div>

                        <div class="text-center small mt-3 py-2" style="font-size: 11px;">
                            <p class="mb-0">ဝယ်ယူအားပေးမှုကို ကျေးဇူးတင်ပါသည်။</p>
                            <small style="font-size: 9px">MSH Team မှ ပံ့ပိုးထားပါသည်။</small>
                        </div>
                    </div>

                    <div class="mt-1 d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-light btn-sm text-nowrap w-100"
                            data-bs-dismiss="modal">ပိတ်မည်</button>
                        <button type="button" class="btn btn-primary btn-sm text-nowrap w-100 fw-bold"
                            onclick="printReceiptSlip()">
                            <i class="bi bi-printer"></i> ဘောင်ချာထုတ်ရန်
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
            if (amount === undefined || amount === null || isNaN(amount)) amount = 0;
            return Number(amount).toLocaleString() + ' ကျပ်';
        }

        async function viewReceipt(id) {
            try {
                const response = await fetch(`/sales/${id}`);
                if (!response.ok) {
                    alert('ဘောင်ချာအချက်အလက်များ ရယူ၍မရပါသဖြင့် ထပ်မံကြိုးစားကြည့်ပါ။');
                    return;
                }
                const sale = await response.json();

                // Populate receipt data
                document.getElementById('recDate').textContent = sale.sale_date || '-';
                document.getElementById('recInvoice').textContent = sale.invoice_number || '-';
                document.getElementById('recCustomer').textContent = sale.customer_name || 'ပုံမှန်ဝယ်ယူသူ';
                document.getElementById('recCashier').textContent = sale.cashier || '-';

                const recItems = document.getElementById('recItems');
                if (sale.items && sale.items.length > 0) {
                    recItems.innerHTML = sale.items.map(item => `
                        <tr>
                            <td class="text-start">${item.name || 'N/A'}</td>
                            <td class="text-center">${item.quantity || 0}</td>
                            <td class="text-end">${formatMoney(item.subtotal || 0)}</td>
                        </tr>
                    `).join('');
                } else {
                    recItems.innerHTML = `<tr><td colspan="3" class="text-center">ပစ္စည်းများမရှိပါ</td></tr>`;
                }

                const subtotal = sale.items ? sale.items.reduce((sum, item) => sum + (item.subtotal || 0), 0) : 0;
                const discount = sale.discount || 0;

                document.getElementById('recSubtotal').textContent = formatMoney(subtotal);
                document.getElementById('recDiscount').textContent = formatMoney(discount);
                document.getElementById('recTotal').textContent = formatMoney(sale.total_amount || 0);

                document.getElementById('recMethod').textContent = sale.payment_method ? sale.payment_method.toUpperCase() : 'ငွေသား';

                const recReceivedLine = document.getElementById('recReceivedLine');
                const recChangeLine = document.getElementById('recChangeLine');

                if (sale.payment_method === 'cash') {
                    recReceivedLine.style.display = 'block';
                    recChangeLine.style.display = 'block';
                    document.getElementById('recReceived').textContent = formatMoney(sale.payment_amount || 0);
                    document.getElementById('recChange').textContent = formatMoney(sale.change_amount || 0);
                } else {
                    recReceivedLine.style.display = 'none';
                    recChangeLine.style.display = 'none';
                }

                const modal = new bootstrap.Modal(document.getElementById('receiptModal'));
                modal.show();
            } catch (e) {
                console.error('Error:', e);
                alert('တစ်စုံတစ်ရာ မှားယွင်းနေပါသည်။ ထပ်မံကြိုးစားကြည့်ပါ။');
            }
        }

        function printReceiptSlip() {
            const printContent = document.getElementById('receiptSlipArea').innerHTML;
            const printWindow = window.open('', '_blank', 'width=400,height=600');
            
            printWindow.document.write(`
                <html>
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>ဘောင်ချာထုတ်ရန်</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Myanmar:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
                    <style>
                        body {
                            padding: 20px;
                            font-family: 'Noto Sans Myanmar', 'Pyidaungsu', 'Myanmar Text', 'Zawgyi-One', monospace;
                            width: 80mm;
                            max-width: 80mm;
                            margin: 0 auto;
                        }
                        @page {
                            size: 80mm auto;
                            margin: 0mm;
                        }
                        .text-center { text-align: center; }
                        .fw-bold { font-weight: bold; }
                        .small { font-size: 11px; }
                        .mb-0 { margin-bottom: 0; }
                        .mb-1 { margin-bottom: 4px; }
                        .mb-2 { margin-bottom: 8px; }
                        .mt-3 { margin-top: 12px; }
                        .py-2 { padding-top: 8px; padding-bottom: 8px; }
                        .my-1 { margin-top: 4px; margin-bottom: 4px; }
                        .my-2 { margin-top: 8px; margin-bottom: 8px; }
                        .w-100 { width: 100%; }
                        .text-start { text-align: left; }
                        .text-center { text-align: center; }
                        .text-end { text-align: right; }
                        .d-flex { display: flex; }
                        .justify-content-between { justify-content: space-between; }
                        table { border-collapse: collapse; width: 100%; }
                        td, th { padding: 2px 4px; }
                    </style>
                </head>
                <body>
                    ${printContent}
                    <script>
                        window.onload = function() {
                            window.print();
                        }
                    <\/script>
                </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.focus();
        }
    </script>
</body>

</html>