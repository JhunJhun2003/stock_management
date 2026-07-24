<!doctype html>
<html lang="my">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>မသီတာပြုံး - ကုန်ပစ္စည်းများ </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image" href="{{ asset('img/logo.jpg') }}">
    {{-- <link rel="stylesheet" href="product_management.css"> --}}
    <link href="{{ asset('css/product_management.css') }}" rel="stylesheet">


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

                        <h5 class="fw-bold text-white p-3 mb-2"
                            style="border-bottom: 1px solid #1e293b; width: 100%; text-align: center;">
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
                            <a class="nav-link text-white {{ request()->routeIs('products.index') ? 'activeRoute' : '' }}"
                                href="{{ route('products.index') }}">
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
                        <h4 class="fw-bold mb-1">ကုန်ပစ္စည်းများ</h4>
                        <small class="text-muted">ကုန်ပစ္စည်းစာရင်းနှင့် လက်ကျန်ကို စီမံခန့်ခွဲပါ။</small>
                    </div>

                    <div class="d-flex gap-2">
                        <!-- Search Box -->
                        <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                            <div class="input-group input-group-sm">
                                <input type="text" name="search" class="form-control" placeholder="ရှာဖွေရန်..."
                                    value="{{ request('search') }}" style="min-width: 200px; font-size: 14px;">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                                @if (request('search'))
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                @endif
                            </div>
                        </form>

                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="bi bi-plus-lg"></i> ကုန်ပစ္စည်းအသစ်ထည့်ရန်
                        </button>
                    </div>
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

                @if (request('search'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-search"></i>
                        "{{ request('search') }}" အတွက် ရလဒ် {{ $products->total() }} ခု တွေ့ရှိခဲ့ပါသည်။
                        <a href="{{ route('products.index') }}" class="alert-link">အားလုံးပြန်ကြည့်ရန်</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i>
                        <strong>အချက်အလက်များ မှားယွင်းနေပါသည်။</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif --}}

                @if (isset($lowStockProducts) && $lowStockProducts->isNotEmpty())
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <strong>လက်ကျန်လျော့နည်းမှု သတိပေးချက်</strong>
                        အောက်ပါပစ္စည်းများသည် လက်ကျန် {{ \App\Models\Product::LOW_STOCK_THRESHOLD }} ခုအောက်
                        လျော့နည်းနေပါသည်။

                        @foreach ($lowStockProducts as $lowStockProduct)
                            <span class="badge bg-warning text-dark ms-1">{{ $lowStockProduct->product_name }} (အိမ်:
                                {{ $lowStockProduct->home_stock }}, ဆိုင်: {{ $lowStockProduct->shop_stock }})</span>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm p-3">
                    <div class="table-responsive product-table-scroll"
                        style="display: block; max-height: calc(100vh - 160px); overflow-y: auto; overflow-x: auto;">
                        <table class="table table-hover align-middle mb-0" style="font-size: 15px">
                            {{-- <thead class="table-light"> --}}
                            <thead class="table-light"
                                style="position: sticky; top: 0;">
                                <tr class="text-center">
                                    <th>ကုဒ်အမှတ်</th>
                                    <th>ပစ္စည်းအမည်</th>
                                    <th>အမျိုးအစား</th>
                                    <th>အိမ်၀ယ်ဈေး</th>
                                    <th>အိမ်ရောင်းဈေး</th>
                                    <th>ဆိုင်၀ယ်ဈေး</th>
                                    <th>ဆိုင်ရောင်းဈေး</th>
                                    <th>အိမ်လက်ကျန်</th>
                                    <th>ဆိုင်လက်ကျန်</th>
                                    <th>အခြေအနေ</th>
                                    <th>လုပ်ဆောင်ချက်</th>
                                </tr>
                            </thead>
                            <tbody class="text-center" style="font-size: 14px;">
                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ $product->product_code }}</td>
                                        <td>{{ $product->product_name }}</td>
                                        <td>{{ ucfirst($product->category ?? 'N/A') }}</td>
                                        <td>{{ number_format($product->home_cost, 0) }} </td>
                                        <td>{{ number_format($product->home_price, 0) }} </td>
                                        <td>{{ number_format($product->shop_cost, 0) }} </td>
                                        <td>{{ number_format($product->shop_price, 0) }} </td>
                                        <td>{{ $product->home_stock }}</td>
                                        <td>{{ $product->shop_stock }}</td>
                                        <td>
                                            @if ($product->isOutOfStock())
                                                <span
                                                    class="badge bg-danger-subtle text-danger">လက်ကျန်မရှိတေ့ာပါ။</span>
                                            @elseif($product->isLowStock())
                                                <span
                                                    class="badge bg-warning-subtle text-warning">လက်ကျန်လျော့နည်းနေပါသည်။</span>
                                            @else
                                                <span
                                                    class="badge bg-success-subtle text-success">လက်ကျန်ရှိပါသည်။</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-light text-primary me-1 edit-product-btn"
                                                data-bs-toggle="modal" data-bs-target="#editProductModal"
                                                data-id="{{ $product->id }}"
                                                data-code="{{ $product->product_code }}"
                                                data-name="{{ $product->product_name }}"
                                                data-category="{{ $product->category }}"
                                                data-home-cost="{{ $product->home_cost }}"
                                                data-shop-cost="{{ $product->shop_cost }}"
                                                data-home-price="{{ $product->home_price }}"
                                                data-shop-price="{{ $product->shop_price }}"
                                                data-home-stock="{{ $product->home_stock }}"
                                                data-shop-stock="{{ $product->shop_stock }}"
                                                data-desc="{{ $product->description }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <button class="btn btn-sm btn-light text-danger delete-product-btn"
                                                data-bs-toggle="modal" data-bs-target="#deleteProductModal"
                                                data-id="{{ $product->id }}"
                                                data-code="{{ $product->product_code }}"
                                                data-name="{{ $product->product_name }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-4">
                                            <i class="bi bi-box-seam fs-1 d-block text-muted"></i>
                                            <p class="text-muted mt-2">
                                                @if (request('search'))
                                                    "{{ request('search') }}" နှင့်ကိုက်ညီသော ပစ္စည်းရှာမတွေ့ပါ။
                                                @else
                                                    ပစ္စည်းရှာမတွေ့ပါ။
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if (isset($products) && method_exists($products, 'links'))
                        <div class="mt-3">
                            {{ $products->appends(request()->query())->links() }}
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
                    <h5 class="modal-title fw-bold" style="font-size: 18px">ပစ္စည်းအသစ်ထည့်ရန်</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="addProductForm" method="POST" action="{{ route('products.store') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">ပစ္စည်းအမည် *</label>
                                <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                                    id="add_prod_name" name="product_name" value="{{ old('product_name') }}"
                                    placeholder="ကုန်ပစ္စည်းအမည် ရိုက်ထည့်ပါ" required style="font-size: 16px">
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">ကုဒ်အမှတ် *</label>
                                <input type="text"
                                    class="form-control bg-light @error('product_code') is-invalid @enderror"
                                    id="add_prod_code" placeholder="ကုဒ်နံပါတ်ရိုက်ထည့်ပါ" maxlength="100"
                                    oninput="formatCode(this)" name="product_code" value="{{ old('product_code') }}"
                                    style="font-size: 16px">
                                @error('product_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">အမျိုးအစား *</label>
                                <select class="form-select" id="add_prod_category" name="category"
                                    style="font-size: 16px" required>
                                    <option value="" disabled {{ old('category') ? '' : 'selected' }}>အမျိုးအစား
                                        ရွေးချယ်ပါ။</option>
                                    <option value="အအေး/ဖျော်ရည်"
                                        {{ old('category') === 'အချိုရည်/ရေ' ? 'selected' : '' }}>အချိုရည်/ရေ</option>
                                    <option value="Tea"
                                        {{ old('category') === 'မုန့်အမျိုးမျိုး' ? 'selected' : '' }}>Tea</option>
                                    <option value="ဝေဖာ" {{ old('category') === 'ဝေဖာ' ? 'selected' : '' }}>ဝေဖာ
                                    </option>
                                    {{-- <option value="electronics">လျှပ်စစ်ပစ္စည်း</option> --}}
                                    <option value="အချဥ်ထုပ်" {{ old('category') === 'အချဥ်ထုပ်' ? 'selected' : '' }}>
                                        အချဥ်ထုပ်</option>
                                    <option value="မီးဖိုချောင်သုံး"
                                        {{ old('category') === 'မီးဖိုချောင်သုံး' ? 'selected' : '' }}>မီးဖိုချောင်သုံး
                                    </option>
                                    <option value="ချိုချဥ်/ပီကေ"
                                        {{ old('category') === 'ချိုချဥ်/ပီကေ' ? 'selected' : '' }}>ချိုချဥ်/ပီကေ
                                    </option>
                                    <option value="ဂျူးချောင်း"
                                        {{ old('category') === 'ဂျူးချောင်း' ? 'selected' : '' }}>ဂျူးချောင်း</option>
                                    <option value="ခေါက်ဆွဲခြောက်"
                                        {{ old('category') === 'ခေါက်ဆွဲခြောက်' ? 'selected' : '' }}>ခေါက်ဆွဲခြောက်
                                    </option>
                                    <option value="ဘီစကစ်" {{ old('category') === 'ဘီစကစ်' ? 'selected' : '' }}>ဘီစကစ်
                                    </option>
                                    <option value="ကိတ်ခြောက်"
                                        {{ old('category') === 'ကိတ်ခြောက်' ? 'selected' : '' }}>ကိတ်ခြောက်</option>
                                    <option value="ဆီကြော်" {{ old('category') === 'ဆီကြော်' ? 'selected' : '' }}>
                                        ဆီကြော်</option>
                                    <option value="ကိတ်မုန့်" {{ old('category') === 'ကိတ်မုန့်' ? 'selected' : '' }}>
                                        ကိတ်မုန့်</option>
                                    <option value="ပေါင်မုန့်"
                                        {{ old('category') === 'ပေါင်မုန့်' ? 'selected' : '' }}>ပေါင်မုန့်</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">အိမ်၀ယ်ဈေး *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light" style="font-size: 14px"> ကျပ် </span>
                                    <input type="number" class="form-control" id="add_prod_home_cost"
                                        name="home_cost" placeholder="၀" value="{{ old('home_cost') }}" required
                                        style="font-size: 16px" step="1" min="0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">အိမ်ရောင်းဈေး *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light" style="font-size: 14px"> ကျပ် </span>
                                    <input type="number" class="form-control" id="add_prod_home_price"
                                        name="home_price" placeholder="၀" value="{{ old('home_price') }}" required
                                        style="font-size: 16px" step="1">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">ဆိုင်၀ယ်ဈေး *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light" style="font-size: 14px"> ကျပ် </span>
                                    <input type="number" class="form-control" id="add_prod_shop_cost"
                                        name="shop_cost" placeholder="၀" value="{{ old('shop_cost') }}" required
                                        style="font-size: 16px" step="1">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">ဆိုင်ရောင်းဈေး *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light" style="font-size: 14px"> ကျပ် </span>
                                    <input type="number" class="form-control" id="add_prod_shop_price"
                                        name="shop_price" placeholder="၀" value="{{ old('shop_price') }}" required
                                        style="font-size: 16px" step="1">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">အိမ်လက်ကျန် *</label>
                                <input type="number" class="form-control" id="add_prod_home_stock"
                                    name="home_stock" placeholder="၀" value="{{ old('home_stock') }}" required
                                    style="font-size: 16px" min="0">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">ဆိုင်လက်ကျန် *</label>
                                <input type="number" class="form-control" id="add_prod_shop_stock"
                                    name="shop_stock" placeholder="၀" value="{{ old('shop_stock') }}" required
                                    style="font-size: 16px" min="0">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">အခြေအနေ</label>
                                <select class="form-select" id="add_prod_status" name="is_active"
                                    style="font-size: 16px">
                                    <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>
                                        အသုံးပြု</option>
                                    <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>ရပ်ဆိုင်း
                                    </option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-medium text-muted">မှတ်ချက်</label>
                                <textarea class="form-control" id="add_prod_desc" name="description" rows="2"
                                    placeholder="ပစ္စည်းအကြောင်းအရာ" style="font-size: 16px">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-top-0">
                        <button type="button" class="btn btn-light btn-sm px-3"
                            data-bs-dismiss="modal">ပယ်ဖျက်ရန်</button>
                        <button type="submit" class="btn btn-primary btn-sm px-3">သိမ်းဆည်းရန်</button>
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
                    <h5 class="modal-title fw-bold" style="font-size: 18px">ကုန်ပစ္စည်းအချက်အလက် ပြင်ဆင်ရန်</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="editProductForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <input type="hidden" id="edit_product_id" name="product_id"
                            value="{{ old('product_id') }}">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">ပစ္စည်းအမည် *</label>
                                <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                                    id="edit_prod_name" name="product_name" value="{{ old('product_name') }}"
                                    required style="font-size: 16px">
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">ကုဒ်နံပါတ်</label>
                                <input type="text"
                                    class="form-control bg-light @error('product_code') is-invalid @enderror"
                                    id="edit_prod_code" name="product_code" value="{{ old('product_code') }}"
                                    readonly style="font-size: 16px">
                                @error('product_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">အမျိုးအစား *</label>
                                <select class="form-select" id="edit_prod_category" name="category"
                                    style="font-size: 16px" required>
                                    <option value="" disabled selected>အမျိုးအစား ရွေးချယ်ပါ။</option>
                                    <option value="အအေး/ဖျော်ရည်"
                                        {{ old('category') === 'အအေး/ဖျော်ရည်' ? 'selected' : '' }}>အအေး/ဖျော်ရည်
                                    </option>
                                    <option value="မုန့်အမျိုးမျိုး"
                                        {{ old('category') === 'မုန့်အမျိုးမျိုး' ? 'selected' : '' }}>မုန့်အမျိုးမျိုး
                                    </option>
                                    <option value="ကုန်ခြောက်"
                                        {{ old('category') === 'ကုန်ခြောက်' ? 'selected' : '' }}>ကုန်ခြောက်</option>
                                    <option value="အဝတ်အထည်" {{ old('category') === 'အဝတ်အထည်' ? 'selected' : '' }}>
                                        အဝတ်အထည်</option>
                                    <option value="ခေါက်ဆွဲခြောက်"
                                        {{ old('category') === 'ခေါက်ဆွဲခြောက်' ? 'selected' : '' }}>ခေါက်ဆွဲခြောက်
                                    </option>
                                    <option value="ခေါက်ဆွဲခြောက်"
                                        {{ old('category') === 'ခေါက်ဆွဲခြောက်' ? 'selected' : '' }}>ခေါက်ဆွဲခြောက်
                                    </option>
                                    <option value="ဘီစကစ်" {{ old('category') === 'ဘီစကစ်' ? 'selected' : '' }}>ဘီစကစ်
                                    </option>
                                    <option value="ကိတ်ခြောက်"
                                        {{ old('category') === 'ကိတ်ခြောက်' ? 'selected' : '' }}>ကိတ်ခြောက်</option>
                                    <option value="ဆီကြော်" {{ old('category') === 'ဆီကြော်' ? 'selected' : '' }}>
                                        ဆီကြော်</option>
                                    <option value="ကိတ်မုန့်" {{ old('category') === 'ကိတ်မုန့်' ? 'selected' : '' }}>
                                        ကိတ်မုန့်</option>
                                    <option value="ပေါင်မုန့်"
                                        {{ old('category') === 'ပေါင်မုန့်' ? 'selected' : '' }}>ပေါင်မုန့်</option>
                                    <option value="အခြား" {{ old('category') === 'အခြား' ? 'selected' : '' }}>အခြား
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">အိမ်၀ယ်ဈေး *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light" style="font-size: 14px"> ကျပ် </span>
                                    <input type="number" class="form-control" id="edit_prod_home_cost"
                                        name="home_cost" value="{{ old('home_cost') }}" required
                                        style="font-size: 16px" step="1" min="0">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">အိမ်ရောင်းဈေး *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light" style="font-size: 14px"> ကျပ် </span>
                                    <input type="number" class="form-control" id="edit_prod_home_price"
                                        name="home_price" value="{{ old('home_price') }}" required
                                        style="font-size: 16px" step="1">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">ဆိုင်၀ယ်ဈေး *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light" style="font-size: 14px"> ကျပ် </span>
                                    <input type="number" class="form-control" id="edit_prod_shop_cost"
                                        name="shop_cost" value="{{ old('shop_cost') }}" required
                                        style="font-size: 16px" step="1">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">ဆိုင်ရောင်းဈေး *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light" style="font-size: 14px"> ကျပ် </span>
                                    <input type="number" class="form-control" id="edit_prod_shop_price"
                                        name="shop_price" value="{{ old('shop_price') }}" required
                                        style="font-size: 16px" step="1">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">အိမ်လက်ကျန် *</label>
                                <input type="number" class="form-control" id="edit_prod_home_stock"
                                    name="home_stock" value="{{ old('home_stock') }}" required
                                    style="font-size: 16px" min="0">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">ဆိုင်လက်ကျန် *</label>
                                <input type="number" class="form-control" id="edit_prod_shop_stock"
                                    name="shop_stock" value="{{ old('shop_stock') }}" required
                                    style="font-size: 16px" min="0">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-medium text-muted">အခြေအနေ</label>
                                <select class="form-select" id="edit_prod_status" name="is_active"
                                    style="font-size: 16px">
                                    <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>
                                        အသုံးပြုမည်</option>
                                    <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>မသုံးပါ
                                    </option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-medium text-muted">ကုန်ပစ္စည်းအကြောင်းအရာ</label>
                                <textarea class="form-control" id="edit_prod_desc" name="description" rows="2" style="font-size: 16px">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-top-0">
                        <button type="button" class="btn btn-light btn-sm px-3"
                            data-bs-dismiss="modal">ပယ်ဖျက်ရန်</button>
                        <button type="submit" class="btn btn-primary btn-sm px-3">အချက်အလက်ပြုပြင်ရန်</button>
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
                    <h5 class="fw-bold mb-3" style="font-size: 16px">ကုန်ပစ္စည်း ဖျက်ရန်</h5>
                    <p class="text-muted small mb-4">ကုန်ပစ္စည်းဖျက်မှာ သေချာသလား? <br />
                        <span id="delete_prod_name" class="fw-bold text-dark"></span> ကို စတော့စာရင်းထဲမှ ဖျက်ပစ်ရန်
                        သေချာပါသလား။ ဤလုပ်ဆောင်ချက်ကို ပြန်ပြင်၍မရပါ။
                    </p>

                    <form id="deleteProductForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="delete_product_id" name="product_id">
                        <div class="d-flex w-full gap-1 justify-content-between">
                            <button type="button" class="btn btn-light btn-sm px-1"
                                data-bs-dismiss="modal">မဖျက်တေ့ာပါ။</button>
                            <button type="submit" class="btn btn-danger btn-sm px-1">ဖျက်မှာ သေချာပါသည်။</button>
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

            // Generate product code
            // function generateProductCode() {
            //     const prefix = 'PRD-';
            //     const timestamp = Date.now().toString().slice(-6);
            //     const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            //     return prefix + timestamp + random;
            // }

            // Prepare add-product modal when it is fully shown and focus the product name
            document.getElementById('addProductModal').addEventListener('shown.bs.modal', function() {
                // const codeInput = document.getElementById('add_prod_code');
                // codeInput.value = generateProductCode();
                setTimeout(() => {
                    const nameInput = document.getElementById('add_prod_name');
                    nameInput.focus({
                        preventScroll: true
                    });
                    nameInput.setSelectionRange(nameInput.value.length, nameInput.value.length);
                }, 50);
            });

            // Open add-product modal when Enter is pressed while not typing in another form field
            document.addEventListener('keydown', function(event) {
                if (event.key !== 'Enter' || event.altKey || event.ctrlKey || event.metaKey || event
                    .shiftKey) {
                    return;
                }

                const activeModal = document.querySelector('.modal.show');
                const activeElement = document.activeElement;
                const activeTag = activeElement?.tagName;
                const isFormField = ['INPUT', 'TEXTAREA', 'SELECT'].includes(activeTag);

                if (activeModal || isFormField) {
                    return;
                }

                event.preventDefault();
                const addModalEl = document.getElementById('addProductModal');
                const addModal = new bootstrap.Modal(addModalEl);
                addModal.show();
            });

            // Edit Product - Populate modal
            document.querySelectorAll('.edit-product-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const code = this.getAttribute('data-code');
                    const name = this.getAttribute('data-name');
                    const category = this.getAttribute('data-category');
                    const homeCost = this.getAttribute('data-home-cost');
                    const shopCost = this.getAttribute('data-shop-cost');
                    const homePrice = this.getAttribute('data-home-price');
                    const shopPrice = this.getAttribute('data-shop-price');
                    const homeStock = this.getAttribute('data-home-stock');
                    const shopStock = this.getAttribute('data-shop-stock');
                    const desc = this.getAttribute('data-desc');

                    document.getElementById('edit_product_id').value = id;
                    document.getElementById('edit_prod_code').value = code;
                    document.getElementById('edit_prod_name').value = name;
                    document.getElementById('edit_prod_category').value = category || '';
                    document.getElementById('edit_prod_home_cost').value = homeCost;
                    document.getElementById('edit_prod_shop_cost').value = shopCost;
                    document.getElementById('edit_prod_home_price').value = homePrice;
                    document.getElementById('edit_prod_shop_price').value = shopPrice;
                    document.getElementById('edit_prod_home_stock').value = homeStock;
                    document.getElementById('edit_prod_shop_stock').value = shopStock;
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

            // Auto-focus search input with keyboard shortcut (Ctrl+F or /)
            document.addEventListener('keydown', function(event) {
                // Check if Ctrl+F or '/' is pressed
                if ((event.ctrlKey && event.key === 'f') || (event.key === '/' && !event.ctrlKey && !event
                        .altKey && !event.metaKey)) {
                    const searchInput = document.querySelector('input[name="search"]');
                    if (searchInput && !document.querySelector('.modal.show')) {
                        event.preventDefault();
                        searchInput.focus();
                        searchInput.select();
                    }
                }
            });
        });

        function formatCode(input) {
            input.value = input.value.slice(0, 100);
        }
    </script>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if (old('product_id'))
                    document.getElementById('editProductForm').action = '/products/{{ old('product_id') }}';
                    var editModal = new bootstrap.Modal(document.getElementById('editProductModal'));
                    editModal.show();
                @else
                    var addModal = new bootstrap.Modal(document.getElementById('addProductModal'));
                    addModal.show();
                @endif
            });
        </script>
    @endif
</body>

</html>
