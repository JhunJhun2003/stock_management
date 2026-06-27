<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <title>သီတာပြုံး - ဆက်တင်များ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
{{-- <link rel="stylesheet" href="setting.css"> --}}
<link rel="icon" type="image" href="{{ asset('img/logo.jpg') }}">
   <link href="{{ asset('css/setting.css') }}" rel="stylesheet">
    
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
                            <a class="nav-link" href="{{ route('reports.index') }}">
                                <i class="bi bi-graph-up"></i> အစီရင်ခံစာများ
                            </a>
                        </li>
                        <li class="nav-item admin-only {{ Auth::user()->isAdmin() ? 'show' : '' }}">
                            <a class="nav-link  text-white  {{ request()->routeIs('settings.index') ? 'activeRoute' : '' }}" 
                                href="{{ route('settings.index') }}">
                                <i class="bi bi-gear"></i> ဆက်တင်များ
                            </a>
                        </li>

                        <!-- Seller Menu Items -->
                        <li class="nav-item seller-only {{ !Auth::user()->isSeller() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('seller.dashboard') }}">
                                <i class="bi bi-speedometer2"></i> ပင်မစာမျက်နှာ
                            </a>
                        </li>
                        <li class="nav-item seller-only {{ !Auth::user()->isSeller() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('pos.index') }}">
                                <i class="bi bi-cpu"></i> အရောင်း
                            </a>
                        </li>
                        <li class="nav-item seller-only {{ !Auth::user()->isSeller() ? 'show' : '' }}">
                            <a class="nav-link" href="{{ route('sales.history') }}">
                                <i class="bi bi-receipt"></i> ရောင်းချမှုမှတ်တမ်း
                            </a>
                        </li>
                        <li class="nav-item seller-only {{ !Auth::user()->isSeller() ? 'show' : '' }}">
                            <a class="nav-link  text-white  {{ request()->routeIs('settings.index') ? 'activeRoute' : '' }}" 
                                href="{{ route('settings.index') }}">
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
            <div class="col-md-10 px-4 py-4 main-container">
                <h4 class="fw-bold mb-2">ဆက်တင်များ</h4>
                <p class="text-muted small mb-4">
                    @if(Auth::user()->isAdmin())
                        ဆက်တင်များနှင့် စိတ်ကြိုက်ရွေးချယ်မှုများကို ပြင်ဆင်နိုင်ပါသည်။
                    @else
                        သင်၏ ကိုယ်ရေးကိုယ်တာအကောင့် ဆက်တင်များနှင့် စကားဝှက်ကို အသစ်ပြင်ဆင်နိုင်ပါသည်။
                    @endif
                </p>

                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="nav flex-column settings-nav gap-1" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            
                            @if(Auth::user()->isAdmin())
                            <!-- <button class="nav-link active admin-setting-card" id="general-tab" data-bs-toggle="pill" data-bs-target="#general-panel" type="button" role="tab">
                                <i class="bi bi-sliders"></i> အထွေထွေ
                            </button>

                            <button class="nav-link admin-setting-card" id="business-tab" data-bs-toggle="pill" data-bs-target="#business-panel" type="button" role="tab">
                                <i class="bi bi-building"></i> ဆိုင်/လုပ်ငန်း အချက်အလက်
                            </button> -->

                            <button class="nav-link admin-setting-card" id="backup-tab" data-bs-toggle="pill" data-bs-target="#backup-panel" type="button" role="tab">
                                <i class="bi bi-database-down"></i> ဒေတာသိမ်းဆည်းခြင်းနှင့် ပြန်လည်ရယူခြင်း
                            </button>
                            @endif

                            <button class="nav-link {{ !Auth::user()->isAdmin() ? 'activeRoute' : '' }}" id="password-tab" data-bs-toggle="pill" data-bs-target="#password-panel" type="button" role="tab">
                                <i class="bi bi-shield-lock"></i> စကားဝှက်ပြောင်းရန်
                            </button>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="tab-content" id="v-pills-tabContent">
                            
                            @if(Auth::user()->isAdmin())
                            <!-- General Settings -->
                            <!-- <div class="tab-pane fade show active admin-setting-card" id="general-panel" role="tabpanel" aria-labelledby="general-tab">
                                <div class="card border-0 shadow-sm p-4">
                                    <h6 class="fw-bold mb-3 border-bottom pb-2">အထွေထွေ</h6>

                                    <form method="POST" action="{{ route('settings.update') }}">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted">အသုံးပြုမည့် ငွေကြေး</label>
                                                <select class="form-select form-select-sm" name="currency">
                                                    <option value="Ks." {{ ($settings->currency ?? 'Ks.') == 'Ks.' ? 'selected' : '' }}>ကျပ်</option>
                                                    <option value="$" {{ ($settings->currency ?? '') == '$' ? 'selected' : '' }}>$ (USD)</option>
                                                    <option value="€" {{ ($settings->currency ?? '') == '€' ? 'selected' : '' }}>€ (Euro)</option>
                                                    <option value="£" {{ ($settings->currency ?? '') == '£' ? 'selected' : '' }}>£ (Pound)</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label small text-muted">ငွေကြေးကုဒ်</label>
                                                <select class="form-select form-select-sm" name="currency_code">
                                                    <option value="MMK" {{ ($settings->currency_code ?? 'MMK') == 'MMK' ? 'selected' : '' }}>MMK</option>
                                                    <option value="USD" {{ ($settings->currency_code ?? '') == 'USD' ? 'selected' : '' }}>USD</option>
                                                    <option value="EUR" {{ ($settings->currency_code ?? '') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                                    <option value="GBP" {{ ($settings->currency_code ?? '') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted">အခွန်နှုန်းထား (%)</label>
                                                <input type="number" class="form-control form-control-sm" name="tax_rate" value="{{ $settings->tax_rate ?? 0 }}" step="0.01" min="0" max="100">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label small text-muted">ဘာသာစကား</label>
                                                <select class="form-select form-select-sm" name="language">
                                                    <option value="en">English</option>
                                                    <option value="my">မြန်မာ</option>
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-sm">ဆက်တင်များ သိမ်းဆည်းမည်။</button>
                                    </form>
                                </div>
                            </div> -->

                            <!-- Business Information -->
                            <!-- <div class="tab-pane fade admin-setting-card" id="business-panel" role="tabpanel" aria-labelledby="business-tab">
                                <div class="card border-0 shadow-sm p-4">
                                    <h6 class="fw-bold mb-3 border-bottom pb-2">ဆိုင်/လုပ်ငန်း အချက်အလက်</h6>
                                    
                                    <form method="POST" action="{{ route('settings.update') }}">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted">ဆိုင်/ လုပ်ငန်း အမည်</label>
                                                <input type="text" class="form-control form-control-sm" name="shop_name" value="{{ $settings->shop_name ?? 'သီတာပြုံး' }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted">ဖုန်းနံပါတ်</label>
                                                <input type="text" class="form-control form-control-sm" name="phone" value="{{ $settings->phone ?? '' }}">
                                            </div>
                                        </div>

                                        <div class="row g-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted">အီးမေးလ်လိပ်စာ</label>
                                                <input type="email" class="form-control form-control-sm" name="email" value="{{ $settings->email ?? '' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small text-muted">ဝဘ်ဆိုက်</label>
                                                <input type="url" class="form-control form-control-sm" name="website" value="{{ $settings->website ?? '' }}">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label class="form-label small text-muted">ဆိုင်လိပ်စာ</label>
                                            <textarea class="form-control form-control-sm" name="address" rows="3">{{ $settings->address ?? '' }}</textarea>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-sm">အချက်အလက် သိမ်းဆည်းမည်</button>
                                    </form>
                                </div>
                            </div> -->

                            <!-- Backup & Restore -->
                            <div class="tab-pane fade admin-setting-card" id="backup-panel" role="tabpanel" aria-labelledby="backup-tab">
                                <div class="card border-0 shadow-sm p-4">
                                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                                        <div>
                                            <h6 class="fw-bold mb-1">ဒေတာသိမ်းဆည်းခြင်းနှင့် ပြန်လည်ရယူခြင်း</h6>
                                            <small class="text-muted">Local server ပေါ်တွင် ဒေတာများကို တိုက်ရိုက် စီမံခန့်ခွဲပါ။</small>
                                        </div>
                                        <form method="POST" action="{{ route('settings.backup') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="bi bi-cloud-plus-fill me-1"></i> Auto Backup အခုပဲ လုပ်မည်
                                            </button>
                                        </form>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle" style="font-size: 16px">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Backup ဖိုင်အမည်</th>
                                                    <th>ရက်စွဲနှင့် အချိန်</th>
                                                    <th>ဖိုင်ပမာဏ (Size)</th>
                                                    <th class="text-end">လုပ်ဆောင်ချက်များ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($backups ?? [] as $backup)
                                                <tr>
                                                    <td><i class="bi bi-database text-secondary me-2"></i>{{ $backup['name'] }}</td>
                                                    <td class="text-muted">{{ $backup['date'] }}</td>
                                                    <td>{{ $backup['size'] }}</td>
                                                    <td class="text-end">
                                                        <form method="POST" action="{{ route('settings.restore') }}" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="backup_file" value="{{ $backup['name'] }}">
                                                            <button type="submit" class="btn btn-sm btn-outline-warning me-1" onclick="return confirm('ဤ Backup ဖိုင်ကို ပြန်လည်ရယူရန် သေချာပါသလား? လက်ရှိ ဒေတာများအားလုံး ပျက်ပြယ်သွားမည်ဖြစ်ပါသည်။')">
                                                                <i class="bi bi-arrow-clockwise"></i> ဒေတာပြန်ယူမည်
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-4">
                                                        <i class="bi bi-inbox fs-1 d-block text-muted"></i>
                                                        <p class="text-muted mt-2">Backup ဖိုင်များ မရှိသေးပါ။</p>
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Change Password -->
                            <div class="tab-pane fade {{ !Auth::user()->isAdmin() ? 'show active' : '' }}" id="password-panel" role="tabpanel" aria-labelledby="password-tab">
                                <div class="card border-0 shadow-sm p-4">
                                    <h6 class="fw-bold mb-3 border-bottom pb-2">စကားဝှက်ပြောင်းရန်</h6>
                                    
                                    <form method="POST" action="{{ route('settings.password') }}">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="mb-4">
                                            <label class="form-label small text-muted">လက်ရှိ စာကားဝှက်</label>
                                            <input type="password" class="form-control form-control-sm @error('current_password') is-invalid @enderror custom-placeholder" 
                                                name="current_password" placeholder="လက်ရှိ စကားဝှက်ကို ရိုက်ထည့်ပါ။" required>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label class="form-label small text-muted">စကားဝှက်အသစ်</label>
                                            <input type="password" class="form-control form-control-sm @error('password') is-invalid @enderror custom-placeholder" 
                                                name="password" placeholder="စကားဝှက်အသစ် ရိုက်ထည့်ပါ။" required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label class="form-label small text-muted">စကားဝှက်အသစ်အား ထပ်မံရိုက်ထည့်ပါ။</label>
                                            <input type="password" class="form-control form-control-sm custom-placeholder" 
                                                name="password_confirmation" placeholder="စကားဝှက်အသစ်အား ထပ်မံရိုက်ထည့်ပါ။" required>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary btn-sm">စကားဝှက်အသစ်ပြင်ဆင်မည်။</button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
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
                
                // Hide admin settings cards for sellers
                document.querySelectorAll(".admin-setting-card").forEach(el => {
                    el.style.display = "none";
                });
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
        });
    </script>
</body>
</html>