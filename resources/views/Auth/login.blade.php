@extends('layouts.form')
@section('content')

    <div class="login-container">
        <div class="logo">
            <span>🏛️</span>
        </div>

        <h1 class="title">Desa Girimukti</h1>
        <p class="subtitle">
            Memanfaatkan sistem dan mengolah administrasi<br>
            dengan pembaharuan sistem yang memungkinkan<br>
            efektif dan efisien dalam mengolah data segala<br>
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

        <div class="login-section">
            <h2 class="login-title">Login</h2>
            <p class="login-desc">Masuk menggunakan akun yang terdaftar</p>
        </div>

        <form id="loginForm" action="{{ route('login_post') }}" method="POST" class="login-form">
            @csrf
            <div class="form-group">
                <span class="icon">👤</span>
                <input type="text" name="username" id="username" placeholder="Username / No. HP" required>
            </div>

            <div class="form-group password-group">
                <span class="icon">🔒</span>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/auth.js') }}"></script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
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
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endpush
