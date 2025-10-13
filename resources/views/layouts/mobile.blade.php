<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Basic Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#4CAF50">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="LandManage">
    <meta name="description" content="Land Management System Application">

    <!-- Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Icons -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/icons/icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/icons/icon-192x192.png') }}">

    <!-- Microsoft Tiles -->
    <meta name="msapplication-TileImage" content="{{ asset('images/icons/icon-144x144.png') }}">
    <meta name="msapplication-TileColor" content="#4CAF50">

    <title>@yield('title', 'Desa Girimukti - Portal Layanan Digital')</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    {{-- Bootstrap CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

    {{-- AdminLTE CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        /* Custom Styles */
        .navbar-shadow {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        .footer-dark {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        }

        /* Mobile Menu Animation */
        .mobile-menu {
            transition: max-height 0.3s ease;
        }

        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Install Button Style */
        #installBtn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            display: none;
            align-items: center;
            gap: 8px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        #installBtn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        #installBtn:active {
            transform: translateY(0);
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-white navbar-shadow sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                {{-- Logo & Brand --}}
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-mountain text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">Desa Girimukti</h1>
                        <p class="text-xs text-gray-500">Portal Layanan Digital</p>
                    </div>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600 transition duration-200 font-medium">
                        <i class="fas fa-home mr-1"></i> Beranda
                    </a>

                    {{-- Install PWA Button --}}
                    <button id="installBtn" onclick="installPWA()">
                        <i class="fas fa-download"></i> Install App
                    </button>

                    @auth
                        <a href="{{ route('profile') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:shadow-lg transition duration-200">
                            <i class="fas fa-user mr-1"></i> Profile
                        </a>
                    @else
                        <a href="{{ url('/login') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:shadow-lg transition duration-200">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <button id="mobileMenuBtn" class="md:hidden text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            {{-- Mobile Menu --}}
            <div id="mobileMenu" class="mobile-menu max-h-0 overflow-hidden md:hidden">
                <div class="py-4 space-y-3 border-t">
                    <a href="{{ url('/') }}" class="block text-gray-700 hover:text-blue-600 hover:bg-gray-50 px-4 py-2 rounded transition">
                        <i class="fas fa-home mr-2"></i> Beranda
                    </a>

                    {{-- Install PWA Button Mobile --}}
                    <button id="installBtnMobile" onclick="installPWA()" class="w-full text-left px-4 py-2 rounded" style="display:none; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <i class="fas fa-download mr-2"></i> Install App
                    </button>

                    @auth
                        <a href="{{ route('profile') }}" class="block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg text-center">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                    @else
                        <a href="{{ url('/login') }}" class="block bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg text-center">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Alert/Flash Messages --}}
    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg flex items-center justify-between" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-xl"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg flex items-center justify-between" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-grow">
        <div class="container mx-auto px-4 py-6">
            @yield('content')
        </div>
    </main>

    {{-- Footer --}}
    <footer class="footer-dark text-white mt-auto">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Tentang Desa --}}
                <div>
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <i class="fas fa-mountain mr-2"></i> Desa Girimukti
                    </h3>
                    <p class="text-gray-300 text-sm mb-4">
                        Portal layanan digital untuk mempermudah administrasi dan pelayanan kepada masyarakat Desa Girimukti.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center hover:bg-opacity-30 transition">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                {{-- Link Cepat --}}
                <div>
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <i class="fas fa-link mr-2"></i> Link Cepat
                    </h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ url('/') }}" class="text-gray-300 hover:text-white transition flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i> Beranda</a></li>
                        <li><a href="{{ route('kritik_saran.create') }}" class="text-gray-300 hover:text-white transition flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i> Pengaduan</a></li>
                        <li><a href="{{ route('info-layanan') }}" class="text-gray-300 hover:text-white transition flex items-center"><i class="fas fa-chevron-right mr-2 text-xs"></i> Berita & Informasi</a></li>
                    </ul>
                </div>

                {{-- Kontak --}}
                <div>
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <i class="fas fa-phone mr-2"></i> Hubungi Kami
                    </h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-gray-300"></i>
                            <span class="text-gray-300">Jl. Desa Girimukti No. 123<br>Kecamatan, Kabupaten</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-gray-300"></i>
                            <a href="mailto:info@girimukti.desa.id" class="text-gray-300 hover:text-white transition">info@girimukti.desa.id</a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-gray-300"></i>
                            <a href="tel:+62123456789" class="text-gray-300 hover:text-white transition">+62 123 456 789</a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Copyright --}}
            <div class="border-t border-white border-opacity-20 mt-8 pt-6 text-center text-sm text-gray-300">
                <p>&copy; {{ date('Y') }} Desa Girimukti. Seluruh Hak Dilindungi.</p>
                <p class="mt-1">Dibuat dengan <i class="fas fa-heart text-red-400"></i> untuk Pelayanan Masyarakat</p>
            </div>
        </div>
    </footer>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- Chart.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    {{-- DataTables JS --}}
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    {{-- Bootstrap Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Mobile Menu Script --}}
    <script>
        // Mobile Menu Toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobileMenu');
            const icon = this.querySelector('i');

            if (mobileMenu.style.maxHeight) {
                mobileMenu.style.maxHeight = null;
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            } else {
                mobileMenu.style.maxHeight = mobileMenu.scrollHeight + "px";
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

    {{-- PWA Service Worker Script --}}
    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('✅ Service Worker registered successfully:', registration.scope);

                        // Check for updates
                        registration.addEventListener('updatefound', () => {
                            const newWorker = registration.installing;
                            newWorker.addEventListener('statechange', () => {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    console.log('🔄 New version available! Refresh to update.');
                                }
                            });
                        });
                    })
                    .catch((error) => {
                        console.error('❌ Service Worker registration failed:', error);
                    });
            });
        }

        // Install prompt
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent default install prompt
            e.preventDefault();
            deferredPrompt = e;

            // Show custom install buttons
            const installBtn = document.getElementById('installBtn');
            const installBtnMobile = document.getElementById('installBtnMobile');

            if (installBtn) installBtn.style.display = 'flex';
            if (installBtnMobile) installBtnMobile.style.display = 'block';

            console.log('💡 App can be installed');
        });

        // Function to trigger install
        function installPWA() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('✅ User accepted the install prompt');

                        // Hide install buttons
                        const installBtn = document.getElementById('installBtn');
                        const installBtnMobile = document.getElementById('installBtnMobile');
                        if (installBtn) installBtn.style.display = 'none';
                        if (installBtnMobile) installBtnMobile.style.display = 'none';
                    } else {
                        console.log('❌ User dismissed the install prompt');
                    }
                    deferredPrompt = null;
                });
            }
        }

        // Track if app is installed
        window.addEventListener('appinstalled', () => {
            console.log('✅ PWA was installed');
            deferredPrompt = null;

            // Hide install buttons after installation
            const installBtn = document.getElementById('installBtn');
            const installBtnMobile = document.getElementById('installBtnMobile');
            if (installBtn) installBtn.style.display = 'none';
            if (installBtnMobile) installBtnMobile.style.display = 'none';
        });

        // Check if running as PWA
        if (window.matchMedia('(display-mode: standalone)').matches) {
            console.log('🚀 Running as PWA');
            // Hide install buttons if already installed
            const installBtn = document.getElementById('installBtn');
            const installBtnMobile = document.getElementById('installBtnMobile');
            if (installBtn) installBtn.style.display = 'none';
            if (installBtnMobile) installBtnMobile.style.display = 'none';
        }
    </script>

    @stack('scripts')
</body>
</html>
