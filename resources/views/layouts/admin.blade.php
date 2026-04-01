<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Admin') }} - @yield('title')</title>

    <link href="{{ asset('sb-admin-2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="{{ asset('sb-admin-2/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="{{ asset('sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <!-- Toastr -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>

</head>

<body id="page-top">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i> <img src="{{ asset('sb-admin-2/img/gum.png') }}" alt="" style="max-width: 50px; height: auto;"> </i>
                </div>
                <div class="sidebar-brand-text mx-3">ANYS GUM</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            @role('admin')
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Admin Management</div>
            @php
                $isSettingsActive = request()->routeIs('users.index') || request()->routeIs('activity.index');
            @endphp
            <li class="nav-item {{ $isSettingsActive ? 'active' : '' }}">
                <a class="nav-link {{ $isSettingsActive ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="{{ $isSettingsActive ? 'true' : 'false' }}" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Settings</span>
                </a>
                <div id="collapseTwo" class="collapse {{ $isSettingsActive ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Settings:</h6>
                        <a class="collapse-item {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">{{ __('Users') }}</a>
                        <a class="collapse-item {{ request()->routeIs('activity.index') ? 'active' : '' }}" href="{{ route('activity.index') }}">{{ __('Activity Log') }}</a>
                    </div>
                </div>
            </li>
            @endrole

            @if(auth()->check() && auth()->user()->hasAnyRole(['admin', 'user']))
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Menu Management</div>
            @php
                $isMenuActive = request()->routeIs('assets.index') || request()->routeIs('assets.print-index');
            @endphp
            <li class="nav-item {{ $isMenuActive ? 'active' : '' }}">
                <a class="nav-link {{ $isMenuActive ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseThree"
                    aria-expanded="{{ $isMenuActive ? 'true' : 'false' }}" aria-controls="collapseThree">
                    <i class="fas fa-fw fa-bars"></i>
                    <span>Transaksi</span>
                </a>
                <div id="collapseThree" class="collapse {{ $isMenuActive ? 'show' : '' }}" aria-labelledby="headingThree" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Asset:</h6>
                        <a class="collapse-item {{ request()->routeIs('assets.index') ? 'active' : '' }}" href="{{ route('assets.index') }}">{{ __('Data Asset') }}</a>
                        <a class="collapse-item {{ request()->routeIs('assets.print-index') ? 'active' : '' }}" href="{{ route('assets.print-index') }}">{{ __('Print Barcode/QR') }}</a>
                    </div>
                </div>
            </li>
            @endif
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
        
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <h4>Asset Management System</h4>
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle" src="{{ asset('sb-admin-2/img/undraw_profile.svg') }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">@yield('title')</h1>
                    @yield('content')
                </div>

            </div>
            
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; {{ config('app.name') }} 2026 - Created by IT GUM</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('sb-admin-2/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sb-admin-2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('sb-admin-2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <script src="{{ asset('sb-admin-2/js/sb-admin-2.min.js') }}"></script>

    <script src="{{ asset('sb-admin-2/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('sb-admin-2/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('sb-admin-2/js/demo/datatables-demo.js') }}"></script>

    <!-- Toastr scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- JsBarcode for barcode generation -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

    <!-- QRCode.js for QR Code generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    @stack('scripts')

    <script>
        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };
    </script>
</body>
</html>