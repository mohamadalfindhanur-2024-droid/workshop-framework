<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Verifikasi OTP - Workshop Framework</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo">
                                <h3>Workshop Framework</h3>
                            </div>
                            <h4>Verifikasi OTP</h4>
                            <h6 class="font-weight-light">Masukkan kode OTP yang telah dikirim ke email Anda</h6>
                            
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <form id="form-otp" class="pt-3" method="POST" action="{{ route('otp.verify') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="otp">Kode OTP (6 digit)</label>
                                    <input type="text" class="form-control form-control-lg @error('otp') is-invalid @enderror" 
                                           id="otp" name="otp" placeholder="Masukkan kode OTP" 
                                           maxlength="6" pattern="[0-9]{6}" required autofocus>
                                    @error('otp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Kode OTP berlaku selama 5 menit
                                    </small>
                                </div>
                                <div class="mt-3">
                                    <button type="button" id="btn-otp" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                        <span id="btn-otp-text">VERIFIKASI</span>
                                        <span id="btn-otp-spinner" class="d-none">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Memverifikasi...
                                        </span>
                                    </button>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <a href="/login" class="auth-link text-black">Kembali ke Login</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script>
        document.getElementById('btn-otp').addEventListener('click', function() {
            const form = document.getElementById('form-otp');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            document.getElementById('btn-otp-text').classList.add('d-none');
            document.getElementById('btn-otp-spinner').classList.remove('d-none');
            this.disabled = true;
            form.submit();
        });
    </script>
</body>
</html>
