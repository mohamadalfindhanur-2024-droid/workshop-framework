<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Template Styles -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo text-center">
                                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
                            </div>
                            <h4 class="text-center">Hello! let's get started</h4>
                            <h6 class="font-weight-light text-center">Sign in to continue.</h6>
                            
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                    <strong>Success!</strong> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                    <strong>Error!</strong> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            @if (session('status'))
                                <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            
                            <form class="pt-3" method="POST" action="{{ route('login') }}">
                                @csrf
                                
                                <div class="form-group">
                                    <input type="email" 
                                           class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           placeholder="Email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           autocomplete="email" 
                                           autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <input type="password" 
                                           class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Password" 
                                           required 
                                           autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="mt-3 d-grid gap-2">
                                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                        SIGN IN
                                    </button>
                                </div>
                                
                                <div class="my-3 text-center">
                                    <span class="d-block text-muted">or</span>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <a href="{{ route('google.login') }}" class="btn btn-block btn-outline-danger btn-lg font-weight-medium auth-form-btn">
                                        <i class="mdi mdi-google me-2"></i>Login dengan Google
                                    </a>
                                </div>
                                
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> Keep me signed in
                                        </label>
                                    </div>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="auth-link text-black">Forgot password?</a>
                                    @endif
                                </div>
                                
                                @if (Route::has('register'))
                                <div class="text-center mt-4 font-weight-light"> 
                                    Don't have an account? <a href="{{ route('register') }}" class="text-primary">Create</a>
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Template Scripts -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
</body>
</html>
