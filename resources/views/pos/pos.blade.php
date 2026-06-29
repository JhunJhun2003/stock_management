<!doctype html>
<html lang="my">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>သီတာပြုံး - အရောင်း</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<link rel="icon" type="image" href="{{ asset('img/logo.jpg') }}">
    {{-- <link rel="stylesheet" href="pos.css"> --}}
    <link href="{{ asset('css/pos.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div id="paySlipArea">
        <div class="text-center">
            <h5 class="fw-bold mb-0">သီတာပြုံး</h5>
            {{-- <p class="small mb-1"> အမှတ် (၁၂၃)၊ ရန်ကုန်မြို့၊ ရန်ကုန်တိုင်းဒေသကြီး <br> ဖုန်း - ၀၉ ၁၂၃၄၅၆၇၈၉ </p> --}}
            <div class="receipt-dash"></div>
        </div>

        <div class="small my-2">
            <div><strong>ရက်စွဲ။</strong> <span id="rDate">{{ now()->format('d-M-Y') }}</span></div>
            <div><strong>ဘောင်ချာနံပါတ်။</strong> <span id="rInvoice">-</span></div>
            <div><strong>ဝယ်ယူသူ။</strong> <span id="rCustomer">-</span></div>
            <div><strong>အရောင်းဝန်ထမ်း။</strong> <span id="rCashier">{{ Auth::user()->name }}</span></div>
        </div>

        <div class="receipt-dash"></div>

        <table class="w-100 small my-2">
            <thead>
                <tr>
                    <th class="text-start">ပစ္စည်း</th>
                    <th class="text-center">အရေအတွက်</th>
                    <th class="text-end">ဈေးနှုန်း</th>
                </tr>
            </thead>
            <tbody id="receiptItems"></tbody>
        </table>

        <div class="receipt-dash"></div>

        <div class="d-flex justify-content-between small my-1">
            <span>စုစုပေါင်း</span><span id="rSubtotal">၀  </span>
        </div>

        <div class="d-flex justify-content-between small my-1">
            <span>လျှော့ဈေး</span><span id="rDiscount">၀  </span>
        </div>

        <div class="d-flex justify-content-between fw-bold my-1">
            <span>ကျသင့်ငွေ</span><span id="rTotal">၀  </span>
        </div>

        <div class="receipt-dash"></div>

        <div class="small my-1">
            <div><strong>ငွေပေးချေမှု</strong> <span id="billingMethod">ငွေသား</span></div>
            <div id="receivedLine">
                <strong>လက်ခံရရှိငွေ</strong>   <span id="rReceived">၅,၀၀၀</span>
            </div>

            <div id="rChangeRow">
                <strong>ပြန်အမ်းငွေ</strong>   <span id="rChange">၈၀၀</span>
            </div>
        </div>

        <div class="receipt-dash"></div>

        <div class="text-center small mt-3">
            <p class="mb-0">ဝယ်ယူအားပေးမှုကို ကျေးဇူးတင်ပါသည်။</p>
            <small style="font-size: 10px">MSH Team မှ ပံ့ပိုးထားပါသည်။</small>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            
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
                            <a class="nav-link text-white {{ request()->routeIs('pos.index') ? 'activeRoute' : '' }}"
                                href="{{ route('pos.index') }}">
                                <i class="bi bi-cpu"></i> အရောင်း
                            </a>
                        </li>
                        <li class="nav-item seller-only {{ Auth::user()->isSeller() ? 'show' : '' }}">
                            <a class="nav-link text-white" href="{{ route('sales.history') }}">
                                <i class="bi bi-receipt"></i> ရောင်းချမှုမှတ်တမ်း
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

            <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3 main-container">
                <div class="row">

                      
                    <div class="d-flex justify-content-between align-items-center mb-2 bg-white p-2 rounded-3 shadow-sm border">
                        <div id="currentDateTime" class="small text-muted" style="font-size: 12px"> -  </div>

                        <div class="text-end">
                            <div class="fw-medium text-dark" id="userTitle" style="font-size: 12px">
                                {{ Auth::user()->name }}
                            </div>
                            <div class="text-muted" id="userRoleBadge" style="font-size: 12px">
                                {{ Auth::user()->isAdmin() ? 'စီမံခန့်ခွဲသူ' : 'အရောင်းဝန်ထမ်း' }}
                            </div>
                        </div>

                    </div>

                      

                    <div class="col-md-9 py-3">

                        <div class="d-flex mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" id="productSearch"
                                    placeholder="ကုဒ်နံပါတ်ဖြင့် ရှာဖွေရန် သို့မဟုတ် စကန်ဖတ်ရန်">
                            </div>
                        </div>

                        <ul class="nav nav-pills mb-4 gap-2" id="categoryFilters">
                            <li class="nav-item">
                                <a class="nav-link active btn-sm category-filter" href="#"
                                    data-category="all">အားလုံး</a>
                            </li>
                            @foreach ($categories as $category)
                                <li class="nav-item">
                                    <a class="nav-link bg-white border btn-sm category-filter" href="#"
                                        data-category="{{ $category }}">{{ ucfirst($category) }}</a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                            <table class="table table-hover align-middle border text-nowrap text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-2">စဉ်</th>
                                        <th class="py-2">ကုဒ်နံပါတ်</th>
                                        <th class="py-2">ပစ္စည်းအမည်</th>
                                        <th class="py-2">ဈေးနှုန်း</th>
                                        <th class="py-2">လက်ကျန်</th>
                                        <th class="py-2">လုပ်ဆောင်ချက်</th>
                                    </tr>
                                </thead>
                                <tbody id="productTableBody">
                                    @forelse($products as $product)
                                        <tr class="product-item" role="button" data-id="{{ $product->id }}"
                                            data-code="{{ $product->product_code }}"
                                            data-name="{{ $product->product_name }}"
                                            data-search-name="{{ strtolower($product->product_name) }}"
                                            data-category="{{ $product->category }}"
                                            data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">

                                            <td class="py-2">{{ $loop->iteration }}</td>
                                            <td class="py-2"><span class="fw-bold">{{ $product->product_code }}</span></td>
                                            <td class="py-2">{{ $product->product_name }}</td>
                                            <td class="py-2 fw-medium">{{ number_format($product->price, 0) }}  </td>
                                            <td class="py-2">
                                                @if ($product->stock <= 0)
                                                    <span class="badge bg-danger px-2 py-1.5">ပစ္စည်းလက်ကျန်မရှိပါ။</span>
                                                @elseif($product->isLowStock())
                                                    <span class="badge bg-warning text-dark px-2 py-1.5">
                                                        {{ $product->stock }} (Low)
                                                    </span>
                                                @else
                                                    <span class="badge bg-success px-2 py-1.5">{{ $product->stock }}</span>
                                                @endif
                                            </td>
                                            <td class="py-2">
                                                <button class="btn btn-sm btn-primary add-to-cart-btn" type="button" {{ $product->stock <= 0 ? 'disabled' : '' }}> 
                                                    <i class="bi bi-plus"></i> ထပ်ထည့်ရန်
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5">ပစ္စည်းလက်ကျန်မရှိပါ။</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-3 paySlip p-3 mt-3 border d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-2">လက်ရှိရောင်းချနေမှု</h6>
                                <button class="btn btn-sm btn-outline-danger py-0" id="clearCartBtn" type="button">ပယ်ဖျက်ရန်</button>
                            </div>

                            <div class="mb-3 p-2 bg-light rounded border-start border-primary border-3">
                                <label for="customerNameInput" class="form-label small text-muted mb-1 d-flex justify-content-between">
                                    <span>ဝယ်ယူသူအမည်</span>
                                    <span style="font-size: 11px;">(မထည့်လည်းရပါသည်)</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" id="customerNameInput" placeholder="ပုံမှန်ဝယ်ယူသူ (General)">
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm align-middle text-nowrap text-center mb-0" style="font-size: 14px">
                                    <thead>
                                        <tr class="text-muted">
                                            <th class="py-2 text-start">ပစ္စည်း</th>
                                            <th class="py-2">အရေအတွက်</th>
                                            <th class="py-2 text-end">ဈေးနှုန်း</th>
                                            <th class="py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cartBody">
                                        <tr id="emptyCartRow">
                                            <td colspan="4" class="text-muted text-center py-4 small">ပစ္စည်းများ ထည့်ထားခြင်းမရှိသေးပါ။</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between small mb-2">
                                <span>စုစုပေါင်း</span>
                                <span id="cartSubtotal" class="fw-semibold">ကျပ် ၀</span>
                            </div>

                            <div class="d-flex justify-content-between small mb-2 align-items-center">
                                <span>လျှော့ဈေး</span>
                                <input type="number" class="form-control form-control-sm text-end w-50"
                                    id="discountInput" value="0" min="0" step="1">
                            </div>

                            <div class="d-flex justify-content-between fw-bold h5 my-4 text-success">
                                <span>စုစုပေါင်းကျသင့်ငွေ</span>
                                <span id="cartTotal">ကျပ် ၀</span>
                            </div>

                            <div class="row g-2">
                                <div class="col-8">
                                    <button class="btn btn-success w-100 py-2 small fw-bold" id="payBtn"
                                        data-bs-toggle="modal" data-bs-target="#paymentModal" type="button" disabled> 
                                        ငွေရှင်းပြီး သိမ်းဆည်းရန်
                                    </button>
                                </div>

                                <div class="col-4">
                                    <button class="btn btn-primary w-100 py-2 small" id="holdBtn" type="button" disabled>
                                        ဆိုင်းငံ့ရန်
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light border-0 py-3">
                        <h6 class="modal-title fw-bold text-dark">
                            <i class="bi bi-wallet2 text-primary me-2"></i>ငွေပေးချေမှု
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <form id="paymentForm">
                        <div class="modal-body p-4 pt-2">
                            <div class="text-center mb-3 p-3 bg-success-subtle rounded-3">
                                <span class="text-muted small d-block text-uppercase fw-medium mb-1">ပေးချေရမည့်ငွေ</span>
                                <h3 class="fw-bold text-success mb-0" id="modalTotal">  ၀</h3>
                            </div>

                            <div class="mb-3 d-none">
                                <label class="form-label small fw-medium text-muted mb-2">ငွေပေးချေသည့်နည်းလမ်း</label>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="payMethod" id="payCash"
                                            checked value="cash">
                                        <label class="btn btn-outline-primary w-100 py-2 btn-sm" for="payCash">
                                            <i class="bi bi-cash d-block mb-1 fs-5"></i> ငွေသား
                                        </label>
                                    </div>

                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="payMethod" id="payKPay"
                                            value="kpay">
                                        <label class="btn btn-outline-primary w-100 py-2 btn-sm" for="payKPay">
                                            <i class="bi bi-qr-code d-block mb-1 fs-5"></i> KPay
                                        </label>
                                    </div>

                                    <div class="col-4">
                                        <input type="radio" class="btn-check" name="payMethod" id="payCard"
                                            value="card">
                                        <label class="btn btn-outline-primary w-100 py-2 btn-sm" for="payCard">
                                            <i class="bi bi-credit-card d-block mb-1 fs-5"></i> ကတ်
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <hr class="text-secondary my-3">

                            <div id="cashSection">
                                <div class="mb-3">
                                    <label class="form-label small fw-medium text-muted">လက်ခံရရှိငွေ ( )</label>
                                    <input type="number"
                                        class="form-control form-control-lg fw-bold text-end text-primary"
                                        id="cashReceived" value="0" step="100" min="0">
                                </div>

                                <div
                                    class="d-flex justify-content-between align-items-center p-2 bg-light rounded-2 border mb-4">
                                    <span class="small fw-medium text-muted">ပြန်အမ်းငွေ</span>
                                    <span class="fw-bold text-success h5 mb-0" id="changeAmountDisplay">  ၀</span>
                                </div>
                            </div>

                            <div id="forkpay" style="display: none">
                                <div class="text-center p-3 border rounded-3 bg-light mb-3">
                                    <i class="bi bi-qr-code-scan display-4 text-primary d-block mb-2"></i>
                                    <span class="fw-bold d-block small">KPay QR ကုဒ်ကို စကန်ဖတ်ပါ</span>
                                    <span class="text-muted small" style="font-size: 14px">သီတာပြုံး</span>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-medium text-muted">ငွေပေးချေမှုနံပါတ် (နောက်ဆုံး ၆ လုံး)</label>
                                    <input type="text" class="form-control text-center fw-bold"
                                        placeholder="ဥပမာ - ၁၂၃၄၅၆" maxlength="6">
                                </div>
                            </div>

                            <div id="cardSection" style="display: none">
                                <div class="text-center p-4 border rounded-3 bg-light mb-4">
                                    <i class="bi bi-vimeo text-info display-4 d-block mb-2"></i>
                                    <span class="fw-bold d-block small text-warning"><i
                                            class="bi bi-info-circle me-1"></i>စက်မှ တုံ့ပြန်မှုကို စောင့်ဆိုင်းနေသည်...</span>
                                    <span class="text-muted small d-block mt-1" style="font-size: 14px">ကတ်အား POS စက်ပေါ်တွင် Swipe လုပ်ပါ သို့မဟုတ် ထိပါ</span>
                                </div>
                            </div>

                            <div class="row g-2 d-flex align-items-stretch">
                                <div class="col-6 d-flex">
                                    <button type="button" class="btn btn-light btn-sm w-100 py-2 d-flex align-items-center justify-content-center" data-bs-dismiss="modal">
                                        ပယ်ဖျက်ရန်
                                    </button>
                                </div>

                                <div class="col-6 d-flex">
                                    <button type="button" class="btn btn-success btn-sm w-100 py-2 fw-bold d-flex align-items-center justify-content-center text-center" id="confirmPaymentBtn">
                                        <div>
                                            <i class="bi bi-printer me-1"></i> အတည်ပြုပြီး<br>စာရွက်ထုတ်ရန်
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                const isAdmin = {{ Auth::user()->isAdmin() ? 'true' : 'false' }};
                const checkoutUrl = @json(route('pos.checkout'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

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

                const cart = {};
                let activeCategory = 'all';

                const productSearch = document.getElementById('productSearch');
                const currentDateTimeEl = document.getElementById('currentDateTime');
                const cartBody = document.getElementById('cartBody');
                const cartSubtotalEl = document.getElementById('cartSubtotal');
                const cartTotalEl = document.getElementById('cartTotal');
                const discountInput = document.getElementById('discountInput');
                const payBtn = document.getElementById('payBtn');
                const modalTotal = document.getElementById('modalTotal');
                const cashInput = document.getElementById('cashReceived');
                const changeDisplay = document.getElementById('changeAmountDisplay');
                const confirmBtn = document.getElementById('confirmPaymentBtn');

                function formatMoney(amount) {
                    return '  ' + Number(amount).toLocaleString();
                }

                // Autofocus and select search input for immediate scanning/typing
                if (productSearch) {
                    // focus after tiny timeout to ensure any UI focus traps are settled
                    setTimeout(() => {
                        productSearch.focus({ preventScroll: true });
                        productSearch.select();
                    }, 50);
                }

                // Live date/time updater for header
                function updateDateTime() {
                    if (!currentDateTimeEl) return;
                    const now = new Date();
                    // Format: DD/MM/YYYY HH:MM:SS
                    const opts = { year: 'numeric', month: '2-digit', day: '2-digit' };
                    const datePart = now.toLocaleDateString(undefined, opts);
                    const timePart = now.toLocaleTimeString(undefined, { hour12: false });
                    currentDateTimeEl.textContent = `${datePart} ${timePart}`;
                }

                updateDateTime();
                setInterval(updateDateTime, 1000);

                function getSubtotal() {
                    return Object.values(cart).reduce((sum, item) => sum + (item.price * item.quantity), 0);
                }

                function getDiscount() {
                    return Math.max(0, parseFloat(discountInput.value) || 0);
                }

                function getTotal() {
                    return Math.max(0, getSubtotal() - getDiscount());
                }

                function updateTotals() {
                    const subtotal = getSubtotal();
                    const discount = getDiscount();
                    const total = getTotal();

                    cartSubtotalEl.textContent = formatMoney(subtotal);
                    cartTotalEl.textContent = formatMoney(total);
                    modalTotal.textContent = formatMoney(total);

                    const hasItems = Object.keys(cart).length > 0;
                    payBtn.disabled = !hasItems;
                    document.getElementById('holdBtn').disabled = !hasItems;

                    if (cashInput) {
                        const received = parseFloat(cashInput.value) || 0;
                        const change = received - total;
                        if (change >= 0) {
                            changeDisplay.textContent = formatMoney(change);
                            changeDisplay.className = 'fw-bold text-success h5 mb-0';
                        } else {
                            changeDisplay.textContent = 'Insufficient Cash';
                            changeDisplay.className = 'fw-bold text-danger small mb-0';
                        }
                    }
                }

                function renderCart() {
                    cartBody.innerHTML = '';
                    const items = Object.values(cart);

                    if (items.length === 0) {
                        cartBody.innerHTML =
                            '<tr><td colspan="4" class="text-muted text-center small">No items in cart</td></tr>';
                        updateTotals();
                        return;
                    }

                    items.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
              <td>${item.name}</td>
              <td>
                <div class="d-flex align-items-center gap-1">
                  <button type="button" class="btn btn-sm btn-light py-0 px-1 qty-minus" data-id="${item.id}">-</button>
                  <span>${item.quantity}</span>
                  <button type="button" class="btn btn-sm btn-light py-0 px-1 qty-plus" data-id="${item.id}">+</button>
                </div>
              </td>
              <td>${formatMoney(item.price * item.quantity)}</td>
              <td><button type="button" class="btn btn-sm btn-link text-danger p-0 remove-item" data-id="${item.id}"><i class="bi bi-x-lg"></i></button></td>
            `;
                        cartBody.appendChild(row);
                    });

                    updateTotals();
                }

                function addToCart(productEl) {
                    const id = productEl.dataset.id;
                    const stock = parseInt(productEl.dataset.stock, 10);
                    const name = productEl.dataset.name;
                    const price = parseFloat(productEl.dataset.price);

                    if (!cart[id]) {
                        cart[id] = {
                            id,
                            name,
                            price,
                            quantity: 0,
                            stock
                        };
                    }

                    if (cart[id].quantity >= stock) {
                        alert(`Only ${stock} unit(s) available for ${name}.`);
                        return;
                    }

                    cart[id].quantity += 1;
                    renderCart();
                }

                document.querySelectorAll('.product-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        if (e.target.closest('button') || parseInt(this.dataset.stock, 10) > 0) {
                            addToCart(this);
                        }
                    });
                });

                cartBody.addEventListener('click', function(e) {
                    const minusBtn = e.target.closest('.qty-minus');
                    const plusBtn = e.target.closest('.qty-plus');
                    const removeBtn = e.target.closest('.remove-item');

                    if (minusBtn) {
                        const id = minusBtn.dataset.id;
                        if (cart[id].quantity > 1) {
                            cart[id].quantity -= 1;
                        } else {
                            delete cart[id];
                        }
                        renderCart();
                    }

                    if (plusBtn) {
                        const id = plusBtn.dataset.id;
                        const productEl = document.querySelector(`.product-item[data-id="${id}"]`);
                        if (productEl) {
                            addToCart(productEl);
                        }
                    }

                    if (removeBtn) {
                        delete cart[removeBtn.dataset.id];
                        renderCart();
                    }
                });

                document.getElementById('clearCartBtn').addEventListener('click', function() {
                    Object.keys(cart).forEach(key => delete cart[key]);
                    renderCart();
                });

                discountInput.addEventListener('input', updateTotals);

                productSearch.addEventListener('input', filterProducts);
                productSearch.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const query = productSearch.value.trim().toLowerCase();
                        const match = Array.from(document.querySelectorAll('.product-item')).find(item =>
                            item.dataset.code.toLowerCase() === query
                        );
                        if (match && match.style.display !== 'none') {
                            addToCart(match);
                            productSearch.value = '';
                            filterProducts();
                        }
                    }
                });

                document.querySelectorAll('.category-filter').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        document.querySelectorAll('.category-filter').forEach(el => {
                            el.classList.remove('active');
                            el.classList.add('bg-white', 'border');
                        });
                        this.classList.add('active');
                        this.classList.remove('bg-white', 'border');
                        activeCategory = this.dataset.category;
                        filterProducts();
                    });
                });

                function filterProducts() {
                    const query = productSearch.value.trim().toLowerCase();

                    document.querySelectorAll('.product-item').forEach(item => {
                        const matchesCategory = activeCategory === 'all' || item.dataset.category ===
                            activeCategory;
                        const matchesSearch = !query ||
                            item.dataset.searchName.includes(query) ||
                            item.dataset.code.toLowerCase().includes(query);

                        item.style.display = matchesCategory && matchesSearch ? '' : 'none';
                    });
                }

                const payCash = document.getElementById('payCash');
                const cashSection = document.getElementById('cashSection');
                const forkpay = document.getElementById('forkpay');
                const cardSection = document.getElementById('cardSection');

                function toggleSections(method) {
                    cashSection.style.display = method === 'cash' ? 'block' : 'none';
                    forkpay.style.display = method === 'kpay' ? 'block' : 'none';
                    cardSection.style.display = method === 'card' ? 'block' : 'none';
                }

                document.querySelectorAll('input[name="payMethod"]').forEach((radio) => {
                    radio.addEventListener('change', (e) => toggleSections(e.target.value));
                });

                document.getElementById('paymentModal').addEventListener('show.bs.modal', function() {
                    cashInput.value = getTotal();
                    updateTotals();
                });

                if (cashInput) {
                    cashInput.addEventListener('input', updateTotals);
                }

                if (confirmBtn) {
                    confirmBtn.addEventListener('click', async function() {
                        const selectedMethod = document.querySelector('input[name="payMethod"]:checked')
                            .value;
                        const total = getTotal();
                        const paymentAmount = selectedMethod === 'cash' ?
                            parseFloat(cashInput.value) || 0 :
                            total;

                        if (selectedMethod === 'cash' && paymentAmount < total) {
                            alert('Payment amount is less than total.');
                            return;
                        }

                        const payload = {
                            items: Object.values(cart).map(item => ({
                                product_id: parseInt(item.id, 10),
                                quantity: item.quantity,
                            })),
                            payment_method: selectedMethod,
                            payment_amount: paymentAmount,
                            discount: getDiscount(),
                            customer_name: document.getElementById('customerNameInput').value.trim() || null,
                        };

                        confirmBtn.disabled = true;

                        try {
                            const response = await fetch(checkoutUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify(payload),
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                alert(data.message || 'Checkout failed.');
                                return;
                            }

                            fillReceipt(data.sale, selectedMethod, paymentAmount);
                            bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();

                            setTimeout(function() {
                                window.print();
                                alert(
                                    'Transaction saved! Cart will now reset for the next customer.'
                                    );
                                Object.keys(cart).forEach(key => delete cart[key]);
                                document.getElementById('customerNameInput').value = '';
                                discountInput.value = 0;
                                renderCart();
                                location.reload();
                            }, 300);
                        } catch (error) {
                            alert('Something went wrong. Please try again.');
                        } finally {
                            confirmBtn.disabled = false;
                        }
                    });
                }

                function fillReceipt(sale, method, paymentAmount) {
                    document.getElementById('rDate').textContent = sale.sale_date;
                    document.getElementById('rInvoice').textContent = sale.invoice_number;
                    document.getElementById('rCustomer').textContent = sale.customer_name || 'Walk-in';

                    const receiptItems = document.getElementById('receiptItems');
                    receiptItems.innerHTML = sale.items.map(item => `
            <tr>
              <td>${item.name}</td>
              <td class="text-center">${item.quantity}</td>
              <td class="text-end">${formatMoney(item.subtotal)}</td>
            </tr>
          `).join('');

                    document.getElementById('rSubtotal').textContent = formatMoney(parseFloat(sale.total_amount) +
                        getDiscount());
                    document.getElementById('rDiscount').textContent = formatMoney(getDiscount());
                    document.getElementById('rTotal').textContent = formatMoney(sale.total_amount);

                    const billingMethod = document.getElementById('billingMethod');
                    const receivedLine = document.getElementById('receivedLine');
                    const rChangeRow = document.getElementById('rChangeRow');

                    billingMethod.textContent = method === 'cash' ? 'Cash' : (method === 'kpay' ? 'KPay' : 'Card');

                    if (method === 'cash') {
                        receivedLine.style.display = 'block';
                        rChangeRow.style.display = 'block';
                        document.getElementById('rReceived').textContent = Number(paymentAmount).toLocaleString();
                        document.getElementById('rChange').textContent = Number(sale.change_amount).toLocaleString();
                    } else {
                        receivedLine.style.display = 'none';
                        rChangeRow.style.display = 'none';
                    }
                }

                renderCart();
            });
        </script>
</body>

</html>
