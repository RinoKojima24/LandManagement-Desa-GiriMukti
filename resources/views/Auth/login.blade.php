<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <script src="https://cdn.tailwindcss.com"></script>


    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Sistem Informasi Bidang Tanah') }}</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>

        .card {
            background: white;
            padding: 20px;
            width: 400px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
            animation: fadeIn 0.3s ease-in-out;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #34d399;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }

        .otp-input {
            width: 60px;
            padding: 12px;
            text-align: center;
            font-size: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

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

                .content {
        flex: 1; /* dorong footer ke bawah */
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
<body class="">

    <div class="content">
        <div class="d-flex justify-content-center">
            <div class="card" style="background: linear-gradient(135deg, #e8f5e8 0%, #f0f8f0 100%);">
                <div class="card-body">
                         <div class="login-container">
                            <div class="logo">
                                <span>🏛️</span>
                            </div>

                            <h1 class="title" style="text-align: center;">Desa Girimukti</h1>
                            <p class="subtitle">
                                Memanfaatkan sistem dan mengolah administrasi
                                dengan pembaharuan sistem yang memungkinkan
                                efektif dan efisien dalam mengolah data segala
                                administrasi dan mengoptimalkan sistem
                            </p>

                            {{-- 🔔 Flasher Message --}}
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-error">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-error">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>⚠️ {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            {{-- End Flasher --}}

                            <div id="LoginFormBox">
                                <div class="login-section">
                                    <h2 class="login-title" style="text-align: center;">Login</h2>
                                    <p class="login-desc" style="text-align: center;">Masuk menggunakan akun yang terdaftar</p>
                                </div>

                                <form id="loginForm" action="{{ route('login_post') }}" method="POST" class="login-form">
                                    @csrf
                                    {{-- <div class="form-group">
                                        <span class="icon">👤</span>
                                        <input type="text" name="username" id="username" placeholder="Username / No. HP" required>
                                    </div> --}}

                                    <div class="form-group">
                                        <span class="icon">👤</span>
                                        <input type="text" name="email" id="email" placeholder="Email / No. HP" required>
                                    </div>

                                    {{-- <div class="form-group password-group">
                                        <span class="icon">🔒</span>
                                        <input type="password" name="password" id="password" placeholder="Password" required>
                                        <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
                                    </div> --}}

                                    {{-- <button type="submit" class="login-btn">Login</button> --}}
                                    <button type="button" id="Lanjutkan" class="login-btn">Login</button>
                                </form>
                            </div>

                            <div id="otpForm" style="display:none;">
                                <center>
                                <h2><b>Masukkan OTP</b></h2>
                                <p>Kode OTP telah dikirim (contoh: 1234)</p><br>

                                <div class="otp-container">
                                    <input maxlength="1" class="otp-input" oninput="moveNext(this, 'otp2')" onkeydown="handleBackspace(event, null, 'otp1')" id="otp1">
                                    <input maxlength="1" class="otp-input" oninput="moveNext(this, 'otp3')" onkeydown="handleBackspace(event, 'otp1', 'otp2')" id="otp2">
                                    <input maxlength="1" class="otp-input" oninput="moveNext(this, 'otp4')" onkeydown="handleBackspace(event, 'otp2', 'otp3')" id="otp3">
                                    <input maxlength="1" class="otp-input" onkeydown="handleBackspace(event, 'otp3', 'otp4')" id="otp4">
                                </div>
                                </center>
                                <br>
                                <button class="login-btn" id="verifikasiOTP">Verifikasi</button>
                            </div>
                        </div>
                        <span>Buat akun jika tidak punya akun? <a href="{{ url('register') }}">Register</a></span>
                </div>
            </div>
        </div>

        <br>
        <div class="container text-center">
            <strong>&copy; {{ date('Y') }} <a href="#">Sistem Informasi Bidang Tanah</a>.</strong>
            All rights reserved. <br> <b>Version</b> 1.0.0
        </div>
    </div>

    <!-- LOADING -->
<div id="loading" style="
    display:none;
    position:fixed;
    top:0; left:0; right:0; bottom:0;
    background:rgba(0,0,0,0.6);
    color:white;
    font-size:20px;
    text-align:center;
    padding-top:20%;
">
    <div>Sedang memproses...</div>
</div>

<!-- NOTIFIKASI -->
<div id="notif" style="
    display:none;
    position:fixed;
    top:20px; right:20px;
    padding:10px 15px;
    background:green;
    color:white;
    border-radius:5px;">
</div>

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

        <script src="{{ asset('js/auth.js') }}"></script>


    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);


        // Pindah ke kolom berikutnya setelah isi 1 digit
        function moveNext(current, nextId) {
            if (current.value.length === 1) {
                document.getElementById(nextId).focus();
            }
        }

        // Tekan backspace → kembali ke kolom sebelumnya
        function movePrev(e, prevId) {
            if (e.key === "Backspace" && e.target.value === "") {
                document.getElementById(prevId).focus();
            }
        }

        function handleBackspace(e, prevId, currentId) {
            if (e.key === "Backspace") {
                let current = document.getElementById(currentId);

                // Jika masih ada isi → hapus dulu tapi jangan pindah
                if (current.value !== "") {
                    current.value = "";
                    e.preventDefault();
                    return;
                }

                // Jika kolom kosong dan ada kolom sebelumnya → pindah & hapus sebelumnya
                if (prevId) {
                    let prev = document.getElementById(prevId);
                    prev.value = "";     // hapus isi sebelumnya
                    prev.focus();        // pindah fokus
                    e.preventDefault();
                }
            }
        }


        // fungsi notif
        function showNotif(text, color = "green") {
            $("#notif")
                .text(text)
                .css("background", color)
                .fadeIn();

            setTimeout(() => $("#notif").fadeOut(), 2000);
        }

        // Enhanced sidebar menu active state
        $(document).ready(function() {
            // Add active class to current menu item
            $('#Lanjutkan').click(function() {
                const email = $('#email').val();

                $("#loading").show();
                $.ajax({
                    url: "{{ url('register/send-otp') }}",
                    type: "POST",
                    data: {
                        email: email,
                        type: 'login',
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        $("#loading").hide();
                        if (res.status === "ok") {
                            $("#LoginFormBox").hide();
                            $("#otpForm").show();
                            $("#otp1").focus();
                        } else if (res.status === "no") {
                            alert(res.message);
                        }
                    }
                });
            });

            $('#verifikasiOTP').click(function() {
                $("#loading").show();
                const nama = $('#nama').val();
                const email = $('#email').val();
                const telepon = $('#telepon').val();
            const otp =
                    $("#otp1").val() +
                    $("#otp2").val() +
                    $("#otp3").val() +
                    $("#otp4").val();

                $.ajax({
                    url: "{{ url('register/check-otp') }}",
                    type: "POST",
                    data: {
                        email: email,

                        otp: otp,
                        type: "login",
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        $("#loading").hide();


                        if (res.status === "ok") {
                            console.log(res);
                            // alert("Registrasi berhasil!");
                            showNotif(res.message);

                            setTimeout(() => {
                                window.location.href = "{{ url('home') }}";
                            }, 2000);
                        } else {
                            // alert("OTP salah!");
                            showNotif(res.message);
                        }
                    }
                });
            });



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
