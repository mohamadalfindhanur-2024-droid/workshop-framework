<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    {{-- ============================================
        i. HEADER - Meta tags, CSRF, dan link ke resource lainnya
    ============================================ --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- ============================================
        ii. STYLE GLOBAL - CSS yang digunakan di semua halaman
    ============================================ --}}
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    
    {{-- ============================================
        iii. STYLE PAGE - CSS khusus untuk halaman tertentu
    ============================================ --}}
    @stack('styles')
    @yield('style_page')
</head>
<body>
    <div class="container-scroller">
        {{-- ============================================
            iv. NAVBAR - Navigation bar di bagian atas
        ============================================ --}}
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
                <a class="navbar-brand brand-logo" href="{{ url('/home') }}">
                    <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" />
                </a>
                <a class="navbar-brand brand-logo-mini" href="{{ url('/home') }}">
                    <img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" />
                </a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="nav-profile-text">
                                <p class="mb-1 text-black">{{ Auth::user()->name }}</p>
                            </div>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="mdi mdi-logout me-2 text-primary"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>

        <div class="container-fluid page-body-wrapper">
            {{-- ============================================
                v. SIDEBAR - Menu navigasi di samping kiri
            ============================================ --}}
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-profile">
                        <a href="#" class="nav-link">
                            <div class="nav-profile-text">
                                <p class="mb-1 text-black">{{ Auth::user()->name }}</p>
                                <span class="text-secondary text-small">{{ Auth::user()->email }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('home') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/home') }}">
                            <span class="menu-title">Dashboard</span>
                            <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('kategori*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('kategori.index') }}">
                            <span class="menu-title">Kategori</span>
                            <i class="mdi mdi-folder menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('buku*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('buku.index') }}">
                            <span class="menu-title">Buku</span>
                            <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('kota*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('kota.index') }}">
                            <span class="menu-title">Kota</span>
                            <i class="mdi mdi-map-marker menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('wilayah*') ? 'active' : '' }}">
                        <a class="nav-link" data-bs-toggle="collapse" href="#wilayah-menu" aria-expanded="{{ Request::is('wilayah*') ? 'true' : 'false' }}">
                            <span class="menu-title">Wilayah</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-map-marker-multiple menu-icon"></i>
                        </a>
                        <div class="collapse {{ Request::is('wilayah*') ? 'show' : '' }}" id="wilayah-menu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('wilayah.ajax') ? 'active' : '' }}" href="{{ route('wilayah.ajax') }}">
                                        Ajax jQuery
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('wilayah.axios') ? 'active' : '' }}" href="{{ route('wilayah.axios') }}">
                                        Axios
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item {{ Request::is('kasir*') ? 'active' : '' }}">
                        <a class="nav-link" data-bs-toggle="collapse" href="#kasir-menu" aria-expanded="{{ Request::is('kasir*') ? 'true' : 'false' }}">
                            <span class="menu-title">Kasir</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-cash-register menu-icon"></i>
                        </a>
                        <div class="collapse {{ Request::is('kasir*') ? 'show' : '' }}" id="kasir-menu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('kasir.ajax') ? 'active' : '' }}" href="{{ route('kasir.ajax') }}">
                                        Ajax jQuery
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('kasir.axios') ? 'active' : '' }}" href="{{ route('kasir.axios') }}">
                                        Axios
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('checkout.marketplace') ? 'active' : '' }}" href="{{ route('checkout.marketplace') }}">
                                        Checkout Marketplace
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item {{ Request::routeIs('customer.*') ? 'active' : '' }}">
                        <a class="nav-link" data-bs-toggle="collapse" href="#customer-menu" aria-expanded="{{ Request::routeIs('customer.*') ? 'true' : 'false' }}">
                            <span class="menu-title">Customer</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-account-multiple menu-icon"></i>
                        </a>
                        <div class="collapse {{ Request::routeIs('customer.*') ? 'show' : '' }}" id="customer-menu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('customer.index') ? 'active' : '' }}" href="{{ route('customer.index') }}">
                                        <i class="mdi mdi-form-textbox me-1"></i> Form Data Customer
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('customer.order-history') ? 'active' : '' }}" href="{{ route('customer.order-history') }}">
                                        <i class="mdi mdi-history me-1"></i> Riwayat Pesanan & QR
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item {{ Request::routeIs('customer.scan') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('customer.scan') }}">
                            <span class="menu-title">
                                <i class="mdi mdi-qrcode-scan me-1"></i> Vendor - Scan QR Pesanan
                            </span>
                        </a>
                    </li>
                    <li class="nav-item {{ Request::is('barang*') ? 'active' : '' }}">
                        <a class="nav-link" data-bs-toggle="collapse" href="#barang-menu" aria-expanded="{{ Request::is('barang*') ? 'true' : 'false' }}">
                            <span class="menu-title">Barang</span>
                            <i class="menu-arrow"></i>
                            <i class="mdi mdi-package-variant menu-icon"></i>
                        </a>
                        <div class="collapse {{ Request::is('barang*') ? 'show' : '' }}" id="barang-menu">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('barang.index') ? 'active' : '' }}" href="{{ route('barang.index') }}">
                                        Cetak Label
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('barang.form-html') ? 'active' : '' }}" href="{{ route('barang.form-html') }}">
                                        Form HTML Table
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ Request::routeIs('barang.form-datatable') ? 'active' : '' }}" href="{{ route('barang.form-datatable') }}">
                                        Form DataTables
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </nav>

            {{-- ============================================
                vi. CONTENT - Area konten utama halaman
            ============================================ --}}
            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>

                {{-- ============================================
                    vii. FOOTER - Informasi footer di bagian bawah
                ============================================ --}}
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
                            Copyright © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    
    @stack('scripts')
    @yield('javascript_page')
</body>
</html>
