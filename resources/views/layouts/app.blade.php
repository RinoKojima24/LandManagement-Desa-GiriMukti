<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Sistem Informasi Bidang Tanah') }}</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    @stack('styles')

    <style>
        /* Mobile First Responsive Design */
        .main-header {
            position: sticky;
            top: 0;
            z-index: 1040;
            border-bottom: 1px solid #dee2e6;
        }

        .brand-link {
            padding: 0.8125rem 0.5rem;
            font-size: 1.125rem;
            line-height: 1.5;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .brand-text {
            font-weight: 600;
        }

        .nav-sidebar .nav-link {
            padding: 0.75rem 1rem;
            word-wrap: break-word;
        }

        .nav-sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 1.2rem;
            text-align: center;
            flex-shrink: 0;
        }

        .content-wrapper {
            min-height: calc(100vh - 57px);
            padding: 0;
        }

        .main-footer {
            margin-top: auto;
            font-size: 0.875rem;
        }

        /* Mobile Styles */
        @media (max-width: 767.98px) {
            /* Hide sidebar by default on mobile */
            .sidebar-mini.sidebar-collapse .main-sidebar {
                margin-left: -250px;
            }

            /* Adjust brand link for mobile */
            .brand-link {
                padding: 0.5rem;
                font-size: 1rem;
            }

            .brand-text {
                display: none;
            }

            .brand-image {
                margin-right: 0 !important;
            }

            /* Content adjustments */
            .content-wrapper {
                padding: 0 10px;
            }

            .content-header {
                padding: 15px 0;
            }

            .content-header h1 {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }

            /* Navbar adjustments */
            .navbar-nav .nav-link {
                padding: 0.5rem 0.75rem;
            }

            /* Dropdown menu adjustments */
            .dropdown-menu-lg {
                width: 280px;
                max-width: calc(100vw - 20px);
            }

            /* User panel adjustments */
            .user-panel {
                padding: 10px;
            }

            .user-panel .info a {
                font-size: 0.9rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 160px;
                display: inline-block;
            }

            /* Footer adjustments */
            .main-footer {
                text-align: center;
                padding: 1rem;
            }

            .main-footer .float-right {
                float: none !important;
                display: block;
                margin-top: 0.5rem;
            }

            /* Alert adjustments */
            .alert {
                margin: 10px 0;
                font-size: 0.9rem;
            }

            /* Breadcrumb adjustments */
            .breadcrumb {
                background: transparent;
                padding: 0;
                margin: 0;
                font-size: 0.85rem;
            }

            /* Navigation improvements */
            .nav-sidebar .nav-link p {
                margin-left: 0.5rem;
            }
        }

        /* Tablet Styles */
        @media (min-width: 768px) and (max-width: 1023.98px) {
            .content-wrapper {
                padding: 0 15px;
            }

            .brand-link {
                font-size: 1rem;
            }
        }

        /* Small mobile devices */
        @media (max-width: 575.98px) {
            .content-wrapper {
                padding: 0 5px;
            }

            .content-header h1 {
                font-size: 1.25rem;
            }

            .dropdown-menu-lg {
                width: 250px;
            }

            /* Stack breadcrumb on very small screens */
            .content-header .row {
                flex-direction: column;
            }

            .breadcrumb {
                justify-content: flex-start;
                margin-top: 0.5rem;
            }

            /* Adjust sidebar for very small screens */
            .main-sidebar {
                width: 280px;
            }
        }

        /* Landscape orientation adjustments */
        @media (max-width: 767.98px) and (orientation: landscape) {
            .content-header {
                padding: 10px 0;
            }

            .content-header h1 {
                font-size: 1.25rem;
            }
        }

        /* Touch-friendly improvements */
        @media (pointer: coarse) {
            .nav-link,
            .dropdown-item,
            .btn {
                min-height: 44px;
                display: flex;
                align-items: center;
            }

            .nav-sidebar .nav-link {
                padding: 12px 16px;
            }
        }

        /* High DPI screen adjustments */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .brand-image,
            .user-panel img {
                image-rendering: -webkit-optimize-contrast;
                image-rendering: crisp-edges;
            }
        }

        /* Improved sidebar overlay for mobile */
        @media (max-width: 767.98px) {
            .sidebar-open .main-sidebar {
                box-shadow: 3px 0 25px rgba(0,0,0,0.11);
            }

            /* Overlay when sidebar is open */
            .sidebar-open::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1030;
                display: block;
            }

            .sidebar-open .main-sidebar {
                z-index: 1035;
            }
        }

        /* Accessibility improvements */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .navbar-light {
                background-color: #343a40 !important;
                border-color: #495057;
            }

            .navbar-light .navbar-nav .nav-link {
                color: #ffffff;
            }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Toggle navigation">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('home') }}" class="nav-link">Home</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">3 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 1 new message
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>

                <!-- User Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="far fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-cogs mr-2"></i> Settings
                        </a>

                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="brand-link">
                <img src="https://via.placeholder.com/33x33/ffffff/000000?text=SI" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">SI Bidang Tanah</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="https://via.placeholder.com/40x40/ffffff/000000?text=U" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block" title="{{ Auth::user()->name ?? 'Administrator' }}">{{ Auth::user()->name ?? 'Administrator' }}</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">



                        <!-- Bidang Tanah -->
                        <li class="nav-item">
                            <a href="{{ route('bidang-tanah.index') }}" class="nav-link {{ request()->routeIs('bidang-tanah.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-map"></i>
                                <p>Bidang Tanah</p>
                            </a>
                        </li>

                        <!-- Data Titik Tanah -->
                        <li class="nav-item">
                            <a href="{{ route('data_titik_tanah') }}" class="nav-link {{ request()->routeIs('data-titik-tanah') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-map-pin"></i>
                                <p>Data Titik Tanah</p>
                            </a>
                        </li>

                        <!-- Users -->
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <!-- surat -->
                        <li class="nav-item">
                            <a href="{{ route('dashboard-permohonan.index') }}" class="nav-link {{ request()->routeIs('surat.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-surat"></i>
                                <p>surat</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('posts.index') }}" class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Blog</p>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('title', 'Dashboard')</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
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
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </section>
        </div>

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>&copy; {{ date('Y') }} <a href="#">Sistem Informasi Bidang Tanah</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>

        <!-- Overlay for mobile sidebar -->
        <div class="sidebar-overlay d-md-none" style="display: none;"></div>
    </div>

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    @stack('scripts')

    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Enhanced sidebar menu active state
        $(document).ready(function() {
            // Add active class to current menu item
            var url = window.location.href;
            $('.nav-sidebar a').each(function() {
                if (this.href === url) {
                    $(this).addClass('active');
                    $(this).closest('.nav-item').addClass('menu-open');
                }
            });

            // Mobile sidebar improvements
            if ($(window).width() <= 767) {
                // Close sidebar when clicking on menu item on mobile
                $('.nav-sidebar .nav-link').on('click', function() {
                    if (!$(this).hasClass('nav-header') && !$(this).find('.right').length) {
                        $('body').removeClass('sidebar-open');
                    }
                });

                // Close sidebar when clicking overlay
                $(document).on('click', '.sidebar-overlay', function() {
                    $('body').removeClass('sidebar-open');
                });
            }

            // Handle window resize
            $(window).on('resize', function() {
                if ($(window).width() > 767) {
                    $('body').removeClass('sidebar-open');
                }
            });

            // Improve touch scrolling on mobile
            if ('ontouchstart' in window) {
                $('.main-sidebar').css({
                    '-webkit-overflow-scrolling': 'touch',
                    'overflow-y': 'auto'
                });
            }

            @yield('jquery')
        });

        // Enhanced pushmenu for mobile
        $(document).on('click', '[data-widget="pushmenu"]', function(e) {
            e.preventDefault();

            if ($(window).width() <= 767) {
                if ($('body').hasClass('sidebar-open')) {
                    $('body').removeClass('sidebar-open');
                    $('.sidebar-overlay').hide();
                } else {
                    $('body').addClass('sidebar-open');
                    $('.sidebar-overlay').show();
                }
            } else {
                // Desktop behavior
                if ($('body').hasClass('sidebar-collapse')) {
                    $('body').removeClass('sidebar-collapse');
                } else {
                    $('body').addClass('sidebar-collapse');
                }
            }
        });

        // Prevent dropdown from closing when clicking inside
        $('.dropdown-menu').on('click', function(e) {
            e.stopPropagation();
        });
    </script>
</body>
</html>
