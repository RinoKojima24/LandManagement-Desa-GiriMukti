<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desa Girimukti - Portal Layanan Digital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <h1>Desa Girimukti</h1>
                <a href="{{ route('home') }}">
                <div class="header-subtitle">Portal Layanan Digital</div></a>
            </div>
           <x-has-unread-notifications />
        </div>
<x-flashher-component/>

        <x-menu_section/>
<!-- Info Section -->
<div class="info-section">
    <div class="info-title">
        <span>Info Terbaru</span>
    </div>
    <div class="info-cards">
        @foreach ($posts as $post)
                        <a href="{{ route('posts.show', $post->id) }}">

            <div class="info-card">
                <img src="{{ $post->thumbnail ? asset('storage/' . $post->thumbnail) : 'https://via.placeholder.com/400x300?text=No+Image' }}"
                     alt="{{ $post->title }}">
                <div class="info-overlay">
                    <p>{{ Str::limit($post->title, 80) }}</p>
                </div>
            </div></a>
        @endforeach
    </div>
</div>

        <!-- Bottom Navigation -->
        <div class="bottom-nav">
            <div class="nav-item active">
                <a href="{{ route('home') }}">
                <i class="fas fa-home"></i>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ url('/data-tanah/titik') }}">
                <i class="fas fa-chart-bar"></i>
                </a>
            </div>
            <div class="nav-item"><a href="{{ route('notifications.index') }}">
                <i class="fas fa-envelope"></i>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('profile') }}">
                <i class="fas fa-user"></i></a>
            </div>
        </div>
    </div>

    <script>
        // Navigation active state
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function() {
                navItems.forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Menu item click handlers dengan feedback visual
        const menuItems = document.querySelectorAll('.menu-item, .secondary-item, .info-card');
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                const text = this.textContent.trim();

                // Tambahkan efek ripple
                const ripple = document.createElement('div');
                ripple.style.position = 'absolute';
                ripple.style.width = '100%';
                ripple.style.height = '100%';
                ripple.style.top = '0';
                ripple.style.left = '0';
                ripple.style.background = 'rgba(255,255,255,0.5)';
                ripple.style.borderRadius = 'inherit';
                ripple.style.pointerEvents = 'none';
                ripple.style.animation = 'ripple 0.6s ease-out';

                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });

        // Tambahkan animasi ripple ke stylesheet
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                0% { opacity: 1; transform: scale(0); }
                100% { opacity: 0; transform: scale(2); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
