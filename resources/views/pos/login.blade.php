<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <title> POS Name - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" >
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" >
    
   {{-- <link href="login.css" rel="stylesheet" > --}}
   <link href="{{ asset('css/login.css') }}" rel="stylesheet">
  </head>

  <body>
    <div class="container">
        <div class="row login-card mx-auto">

            <div class="col-md-5 brand-section text-center">
                <img src="{{ asset('img/logo1.png') }}" alt="POS Logo" class="img-fluid rounded" style="max-height: 90px; width: auto; object-fit: contain">
                <h4 class="fw-bold mb-2">Welcome to</h4>
                <h3 class="fw-bold mb-3">POS Name</h3>
                <p class="small">Manage your sales, products and users easily and efficiently.</p>
            </div>

            <div class="col-md-7 form-section">
                <h4 class="fw-bold mb-1">Login</h4>
                <p class="text-muted small mb-4">Please sign in to continue</p>

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
                        <label class="form-label small fw-medium" for="email">Email Address</label>
                        <input type="email" id="email" name="email" 
                               class="form-control form-control-lg @error('email') is-invalid @enderror" 
                               style="font-size: 16px" 
                               placeholder="Enter email address" 
                               value="{{ old('email') }}" 
                               required autofocus autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-4 text-start">
                        <label class="form-label small fw-medium" for="password">Password</label>
                        <input type="password" id="password" name="password" 
                               class="form-control form-control-lg @error('password') is-invalid @enderror" 
                               style="font-size: 16px" 
                               placeholder="Enter password" 
                               required autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                            <label class="form-check-label small text-muted" for="remember_me">Remember me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-decoration-none small">Forgot password?</a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-primary btn-lg w-100" style="font-size: 16px">
                        Log In
                    </button>
                </form>

                <!-- Optional: Registration Link -->
                @if (Route::has('register'))
                    <p class="text-center mt-3 small text-muted">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-decoration-none">Sign up</a>
                    </p>
                @endif

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Optional: Add your custom JavaScript if needed -->
    <script type="text/javascript">
        // You can add any custom JavaScript here
        // For example, auto-focus on email field
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            if (emailInput) {
                emailInput.focus();
            }
        });
    </script>
</body>
</html>
