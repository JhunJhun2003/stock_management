<!doctype html>
<html lang="my">
  <head>
    <meta charset="UTF-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <title> မသီတာပြုံး  - အကောင့်ဝင်ရန်</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" >
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
   
    <link rel="icon" type="image" href="{{ asset('img/logo.jpg') }}">
   {{-- <link href="login.css" rel="stylesheet" > --}}
   <link href="{{ asset('css/login.css') }}" rel="stylesheet">
  </head>

  <body>
    <div class="container">
        <div class="row login-card mx-auto">

            <div class="col-md-5 brand-section text-center">
                <img src="{{ asset('img/logo.jpg') }}" alt="POS Logo" class="img-fluid rounded-circle mb-2" style="max-height: 120px; width: auto; object-fit: contain">
                <!-- <h4 class="fw-bold mb-3">ကြိုဆိုပါတယ်</h4> -->
                <h3 class="fw-bold mb-3">မသီတာပြုံး </h3>
                <p class="small">သင်၏ အရောင်း၊ ကုန်ပစ္စည်းနှင့် အသုံးပြုသူများကို လွယ်ကူထိရောက်စွာ စီမံခန့်ခွဲနိုင်ပါသည်။</p>
            </div>

            <div class="col-md-7 form-section">
                <h4 class="fw-bold mb-2">အကောင့်ဝင်ရန်</h4>
                <p class="text-muted small mb-4">ဆက်လက်လုပ်ဆောင်ရန် အကောင့်ဝင်ပါ။</p>

                <!-- Show Session Status (e.g., password reset confirmation) -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Show Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3 text-start">
                        <label class="form-label small fw-medium" for="email">အီးမေးလ်လိပ်စာ</label>
                        <input type="email" id="email" name="email" 
                               class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               style="font-size: 16px" 
                               placeholder="အီးမေးလ်ထည့်ပါ" 
                               value="{{ old('email') }}" 
                               required autofocus autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password with Eye Toggle -->
                    <div class="mb-4 text-start position-relative">
                        <label class="form-label small fw-medium" for="password">စကားဝှက်</label>
                        <div class="position-relative">
                            <input type="password" id="password" name="password" 
                                   class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   style="font-size: 16px; padding-right: 45px;" 
                                   placeholder="စကားဝှက်ထည့်ပါ" 
                                   required autocomplete="current-password">
                            <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3" 
                               style="cursor: pointer; font-size: 20px; z-index: 10; color: #6c757d;"
                               onclick="togglePasswordVisibility(this)"></i>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    {{-- <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                            <label class="form-check-label small text-muted" for="remember_me">မှတ်ထားမည်</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-decoration-none small">စကားဝှက် မေ့သွားပါသလား?</a>
                        @endif
                    </div> --}}

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-primary btn-lg w-100" style="font-size: 16px">
                        အကောင့်ဝင်မည်
                    </button>
                </form>

                <!-- Optional: Registration Link -->
                {{-- @if (Route::has('register'))
                    <p class="text-center mt-3 small text-muted">
                        အကောင့်မရှိသေးဘူးလား?
                        <a href="{{ route('register') }}" class="text-decoration-none">အကောင့်အသစ်ဖွင့်ရန်</a>
                    </p>
                @endif --}}

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script type="text/javascript">
        // Password visibility toggle function
        function togglePasswordVisibility(element) {
            const input = element.closest('.position-relative').querySelector('input');
            if (input.type === 'password') {
                input.type = 'text';
                element.classList.remove('bi-eye-slash');
                element.classList.add('bi-eye');
            } else {
                input.type = 'password';
                element.classList.remove('bi-eye');
                element.classList.add('bi-eye-slash');
            }
        }

        // Auto-focus on email field
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            if (emailInput) {
                emailInput.focus();
            }
        });
    </script>
</body>
</html>