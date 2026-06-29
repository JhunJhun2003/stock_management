<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>မသီတာပြုံး  - အစီရင်ခံစာများ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<link rel="icon" type="image" href="{{ asset('img/logo.jpg') }}">
    <link href="{{ asset('css/report.css') }}" rel="stylesheet">
    
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
                            မသီတာပြုံး
                        </h5>
                    </div>

                    <ul class="nav flex-column gap-1">
                        <!-- Admin Menu Items -->
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> ပင်မစာမျက်နှာ
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('users.index') }}">
                                <i class="bi bi-people"></i> အသုံးပြုသူများ
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('products.index') }}">
                                <i class="bi bi-box-seam"></i> ကုန်ပစ္စည်းများ
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link text-white  {{ request()->routeIs('reports.index') ? 'activeRoute' : '' }}"
                                href="{{ route('reports.index') }}">
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
                                <i class="bi bi-cpu"></i> အ‌ရောင်း
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
            <div class="col-md-10 px-4 py-3 ms-sm-auto col-lg-10 px-md-4  main-container">
                <h4 class="fw-bold mb-2">အစီရင်ခံစာများ</h4>

                <!-- Filter Card -->
                <div class="card border-0 shadow-sm p-2 mb-2">
                    <form method="GET" action="{{ route('reports.index') }}" id="reportForm">
                        <div class="card border-0 shadow-sm p-3 mb-2">
                            <div class="row row-cols-lg-8 g-2 align-items-end">
                                    
                                    <div class="col">
                                        <label class="form-label small text-muted mb-1">အစီရင်ခံစာ အမျိုးအစား</label>
                                        <select class="form-select form-select-sm" name="report_type" id="reportType">
                                            <option value="sales" {{ request('report_type') == 'sales' ? 'selected' : '' }}>အရောင်းမှတ်တမ်း</option>
                                            <option value="products" {{ request('report_type') == 'products' ? 'selected' : '' }}>ကုန်ပစ္စည်းမှတ်တမ်း</option>
                                            <option value="users" {{ request('report_type') == 'users' ? 'selected' : '' }}>ဝန်ထမ်းမှတ်တမ်း</option>
                                        </select>
                                    </div>

                                    <div class="col">
                                        <label class="form-label small text-muted mb-1">မှ (ရက်စွဲ)</label>
                                        <input type="date" class="form-control form-control-sm" name="from_date"
                                            value="{{ request('from_date', now()->startOfMonth()->format('Y-m-d')) }}">
                                    </div>

                                    <div class="col">
                                        <label class="form-label small text-muted mb-1">အထိ (ရက်စွဲ)</label>
                                        <input type="date" class="form-control form-control-sm" name="to_date"
                                            value="{{ request('to_date', now()->format('Y-m-d')) }}">
                                    </div>

                                    <div class="col" id="sellerFilter">
                                        <label class="form-label small text-muted mb-1">အရောင်းဝန်ထမ်း</label>
                                        <select class="form-select form-select-sm" name="seller_id">
                                            <option value="">ဝန်ထမ်းအားလုံး</option>
                                            @foreach($sellers ?? [] as $seller)
                                                <option value="{{ $seller->id }}" {{ request('seller_id') == $seller->id ? 'selected' : '' }}>
                                                    {{ $seller->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col" id="customerFilter">
                                        <label class="form-label small text-muted mb-1">ဝယ်သူအမည်</label>
                                        <input type="text" class="form-control form-control-sm" name="customer_name"
                                            placeholder="ဝယ်သူအမည် ထည့်ပါ" value="{{ request('customer_name') }}">
                                    </div>

                                    <div class="col" id="invoiceFilter">
                                        <label class="form-label small text-muted mb-1">ဘောင်ချာနံပါတ်</label>
                                        <input type="text" class="form-control form-control-sm" name="invoice_number" 
                                            placeholder="ဘောင်ချာနံပါတ်" value="{{ request('invoice_number') }}">
                                    </div>

                                    <div class="col">
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <i class="bi bi-search p-2"></i> ရှာဖွေရန်
                                        </button>
                                    </div>

                                    <div class="col">
                                        <button type="button" class="btn btn-success btn-sm w-100" onclick="exportReport()">
                                            <i class="bi bi-file-earmark-excel p-2"></i> သိမ်းဆည်းရန်
                                        </button>
                                    </div>

                                </div>
                        </div>
                    </form>
                </div>

                <!-- Statistics Cards -->
                <div class="row g-3 text-center mb-2">
                    <div class="col-md-3">
                        <div class="stat-card p-3 border rounded bg-white shadow-sm">
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary mb-2">
                                <i class="bi bi-currency-dollar fs-4"></i>
                            </div>
                            <div class="stat-value fw-bold p-1">{{ number_format($totalSales ?? 0) }} ကျပ်</div>
                            <div class="stat-label text-muted small">စုစုပေါင်း ရောင်းရငွေ</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 border rounded bg-white shadow-sm">
                            <div class="stat-icon bg-danger bg-opacity-10 text-danger mb-2">
                                <i class="bi bi-cash-stack fs-4"></i>
                            </div>
                            <div class="stat-value fw-bold p-1"> {{ number_format($totalCost ?? 0) }} ကျပ်</div>
                            <div class="stat-label text-muted small">စုစုပေါင်း အရင်းအနှီး</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 border rounded bg-white shadow-sm">
                            <div class="stat-icon bg-success bg-opacity-10 text-success mb-2">
                                <i class="bi bi-graph-up-arrow fs-4"></i>
                            </div>
                            <div class="stat-value fw-bold text-success p-1"> {{ number_format($netProfit ?? 0) }} ကျပ်</div>
                            <div class="stat-label text-muted small">အမြတ်</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-card p-3 border rounded bg-white shadow-sm">
                            <div class="stat-icon bg-info bg-opacity-10 text-info mb-2">
                                <i class="bi bi-calculator fs-4"></i>
                            </div>
                            <div class="stat-value fw-bold p-1">{{ number_format($averageSale ?? 0) }} ကျပ်</div>
                            <div class="stat-label text-muted small">ပျမ်းမျှ ရောင်းရငွေ</div>
                        </div>
                    </div>
                </div>

                <!-- Report Table -->
                <div class="table-container p-2 bg-white border rounded shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="fw-bold mb-0">
                                {{ $reportTitle ?? 'အရောင်းမှတ်တမ်းအသေးစိတ်' }}
                                @if(request('seller_id') && $reportType === 'sales')
                                    <span class="badge bg-primary ms-2">
                                        ဝန်ထမ်းအလိုက် စစ်ထုတ်ထားသည်။
                                    </span>
                                @endif
                            </h6>
                        </div>
                        <span class="text-muted small">
                            @if($reportType === 'sales')
                                @php
                                    $salesCount = 0;
                                    if(isset($sales)) {
                                        if(method_exists($sales, 'total')) {
                                            $salesCount = $sales->total();
                                        } elseif(is_countable($sales)) {
                                            $salesCount = count($sales);
                                        }
                                    }
                                @endphp
                                {{ $salesCount }} စောင် တွေ့ရှိသည်။
                            @else
                                {{ count($reportData) }} ခု တွေ့ရှိသည်။
                            @endif
                        </span>
                    </div>

                    <div class="table-responsive report-table-scroll">
                        @if($reportType === 'sales')
                            <table class="table table-hover align-middle text-nowrap text-center custom-table mb-0" style="font-size: 14px">
                                <thead class="table-light">
                                    <tr>
                                        <th>ဘောင်ချာနံပါတ်</th>
                                        <th>ရက်စွဲ</th>
                                        <th>ဝယ်သူ</th>
                                        <th>အရောင်းဝန်ထမ်း</th>
                                        <th>ပစ္စည်းအရေအတွက်</th>
                                        <th>စုစုပေါင်းကျသင့်ငွေ</th>
                                        <th>လျှော့ဈေး</th>
                                        <th>ရောင်းရငွေ</th>
                                        <th>ရင်းနှီးငွေ</th>
                                        <th>အမြတ်ငွေ</th>
                                        <th>ငွေပေးချေမှု</th>
                                        <th>လုပ်ဆောင်ချက်</th>
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
                                            <td>{{ $sale->customer_name ?? 'ပုံမှန်ဝယ်ယူသူ' }}</td>
                                            <td>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                    {{ $sale->user->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ $sale->saleDetails->sum('quantity') ?? 0 }}</td>
                                            <td>{{ number_format($sale->total_amount + $sale->discount, 0) }}</td>
                                            <td class="text-danger">{{ number_format($sale->discount, 0) }}</td>
                                            <td class="fw-semibold">{{ number_format($sale->total_amount, 0) }}</td>
                                            <td class="text-danger fw-semibold">{{ number_format($saleCost, 0) }}</td>
                                            <td class="text-success fw-semibold">{{ number_format($saleProfit, 0) }}</td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-10 text-info">
                                                    {{ ucfirst($sale->payment_method ?? 'လက်ငင်းငွေသား') }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="viewReceipt({{ $sale->id }})">
                                                    <i class="bi bi-receipt me-1"></i> ဘောင်ချာကြည့်ရန်
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center py-4">
                                                <i class="bi bi-inbox fs-1 d-block text-muted"></i>
                                                <p class="text-muted mt-2">ရွေးချယ်ထားသော ရက်အတွင်း အရောင်းမှတ်တမ်း မရှိပါ</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="5" class="text-end fw-bold">စုစုပေါင်း</td>
                                        <td class="fw-bold">{{ number_format($sales->sum('total_amount') + $sales->sum('discount'), 0) }}</td>
                                        <td class="fw-bold text-danger">{{ number_format($sales->sum('discount'), 0) }}</td>
                                        <td class="fw-bold">{{ number_format($totalSales ?? 0, 0) }}</td>
                                        <td class="fw-bold text-danger">{{ number_format($totalCost ?? 0, 0) }}</td>
                                        <td class="fw-bold text-success">{{ number_format($netProfit ?? 0, 0) }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>

                        @elseif($reportType === 'products')
                            <table class="table table-hover align-middle text-nowrap text-center custom-table mb-0" style="font-size: 14px">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>ကုဒ်နံပါတ်</th>
                                        <th>ကုန်ပစ္စည်း အမည်</th>
                                        <th>ရောင်းဈေး</th>
                                        <th>ဝယ်ဈေး</th>
                                        <th>စုစုပေါင်း ရောင်းချမှု</th>
                                        <th>စုစုပေါင်း ရရှိငွေ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportData ?? [] as $product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $product->product_code }}</td>
                                            <td>{{ $product->product_name }}</td>
                                            <td>{{ number_format($product->price, 0) }}</td>
                                            <td>{{ number_format($product->cost, 0) }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $product->total_sold ?? 0 }}</span>
                                            </td>
                                            <td class="fw-semibold text-success">
                                                {{ number_format($product->total_revenue ?? 0, 0) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="bi bi-inbox fs-1 d-block text-muted"></i>
                                                <p class="text-muted mt-2">ကုန်ပစ္စည်း အချက်အလက်မရှိပါ။</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        @elseif($reportType === 'users')
                            <table class="table table-hover align-middle text-nowrap text-center custom-table mb-0" style="font-size: 14px">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>ဝန်ထမ်းအမည်</th>
                                        <th>ဘောင်ချာစုစုပေါင်း</th>
                                        <th>စုစုပေါင်းရောင်းရငွေ</th>
                                        <th>ပျမ်းမျှရောင်းရငွေ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportData ?? [] as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->total_sales ?? 0 }}</td>
                                            <td class="fw-semibold text-success">
                                                {{ number_format($user->total_revenue ?? 0, 0) }}
                                            </td>
                                            <td>
                                                {{ number_format(($user->total_sales > 0 ? $user->total_revenue / $user->total_sales : 0), 0) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="bi bi-inbox fs-1 d-block text-muted"></i>
                                                <p class="text-muted mt-2">ဝန်ထမ်းအချက်အလက်မရှိပါ။</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif
                    </div>

                    {{-- Pagination removed: showing all results in the table without page links --}}
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
            <div id="receiptSlipArea" style="font-family: monospace;">
              <div class="text-center">
                <h5 class="fw-bold mb-2">မသီတာပြုံး </h5>
                {{-- <p class="small mb-2"> အမှတ် (၁၂၃)၊ ရန်ကုန်မြို့၊  <br> ဖုန်း - ၀၉ ၁၂၃၄၅၆၇၈၉ </p> --}}
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
                <button type="button" class="btn btn-light btn-sm text-nowrap w-100" data-bs-dismiss="modal">ပိတ်မည်</button>
                <button type="button" class="btn btn-primary btn-sm text-nowrap w-100 fw-bold" onclick="printReceiptSlip()">
                    <i class="bi bi-printer"></i> ဘောင်ချာထုတ်ရန်
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
            const customerFilter = document.getElementById('customerFilter');
            const invoiceFilter = document.getElementById('invoiceFilter');
            
            function toggleSellerFilter() {
                if (reportType.value === 'sales') {
                    sellerFilter.style.display = 'block';
                    customerFilter.style.display = 'block';
                    invoiceFilter.style.display = 'block';
                } else {
                    sellerFilter.style.display = 'none';
                    customerFilter.style.display = 'none';
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
          return '' + Number(amount).toLocaleString();
        }

        async function viewReceipt(id) {
            try {
                const response = await fetch(`/sales/${id}`);
                if (!response.ok) {
                    alert('ဘောင်ချာအချက်အလက်များ ရယူ၍မရပါသဖြင့် ထပ်မံကြိုးစားကြည့်ပါ။');
                    return;
                }
                const sale = await response.json();
                
                document.getElementById('recDate').textContent = sale.sale_date;
                document.getElementById('recInvoice').textContent = sale.invoice_number;
                document.getElementById('recCustomer').textContent = sale.customer_name || 'ပုံမှန်ဝယ်ယူသူ';
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
                alert('တစ်စုံတစ်ရာ မှားယွင်းနေပါသည်။');
            }
        }

        function printReceiptSlip() {
            const printContent = document.getElementById('receiptSlipArea').innerHTML;
            const printWindow = window.open('', '_blank', 'width=400,height=600');
            printWindow.document.write('<html><head><title>ဘောင်ချာထုတ်ရန်</title>');
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
