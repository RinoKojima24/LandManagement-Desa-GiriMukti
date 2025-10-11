@extends('layouts.form')

@section('title', 'Edit Profile')

@push('styles')
<style>
    .toggle-password {
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6 max-w-md lg:max-w-2xl">
    <!-- Header -->
    <div class="mb-6 flex items-center">
        <a href="{{ route('profile') }}" class="mr-4">
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Profile</h1>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Profile Picture Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Foto Profile</h2>
        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <div class="w-24 h-24 rounded-full bg-gray-200 overflow-hidden shadow-md">
                <img id="preview-image"
                     src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? 'User') . '&background=059669&color=fff' }}"
                     alt="Profile Picture"
                     class="w-full h-full object-cover">
            </div>
            <div class="flex-1 text-center sm:text-left">
                <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden">
                <button type="button" onclick="document.getElementById('avatar-input').click()"
                        class="bg-teal-600 hover:bg-teal-700 text-white font-medium px-6 py-2 rounded-lg transition-colors duration-200">
                    Pilih Foto
                </button>
                <p class="text-xs text-gray-500 mt-2">JPG, PNG atau GIF (Max. 2MB)</p>
            </div>
        </div>
    </div>

    <!-- Profile Information Form -->
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Personal</h2>

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name', $user->name ?? '') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all duration-200"
                       placeholder="Masukkan nama lengkap"
                       required>
            </div>


            <!-- Phone -->
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                <input type="tel"
                       id="phone"
                       name="phone"
                       value="{{ old('phone', $user->phone ?? '') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all duration-200"
                       placeholder="08xxxxxxxxxx">
            </div>

            <!-- Save Button -->
            <button type="submit"
                    class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 rounded-lg transition-colors duration-200 shadow-md">
                Simpan Perubahan
            </button>
        </div>
    </form>

    <!-- Change Password Form -->
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Ubah Password</h2>

            <!-- Current Password -->
            <div class="mb-4">
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                <div class="relative">
                    <input type="password"
                           id="current_password"
                           name="current_password"
                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all duration-200"
                           placeholder="Masukkan password saat ini">
                    <button type="button"
                            onclick="togglePassword('current_password')"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 toggle-password">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- New Password -->
            <div class="mb-4">
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                <div class="relative">
                    <input type="password"
                           id="new_password"
                           name="new_password"
                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all duration-200"
                           placeholder="Masukkan password baru">
                    <button type="button"
                            onclick="togglePassword('new_password')"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 toggle-password">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
            </div>

            <!-- Confirm New Password -->
            <div class="mb-4">
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password"
                           id="new_password_confirmation"
                           name="new_password_confirmation"
                           class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent outline-none transition-all duration-200"
                           placeholder="Konfirmasi password baru">
                    <button type="button"
                            onclick="togglePassword('new_password_confirmation')"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 toggle-password">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Change Password Button -->
            <button type="submit"
                    class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 rounded-lg transition-colors duration-200 shadow-md">
                Ubah Password
            </button>
        </div>
    </form>

    <!-- Danger Zone -->
    <div class="bg-white rounded-2xl shadow-md p-6 border-2 border-red-200">
        <h2 class="text-lg font-semibold text-red-600 mb-2">Danger Zone</h2>
        <p class="text-sm text-gray-600 mb-4">Tindakan ini tidak dapat dibatalkan</p>
        <button type="button"
                onclick="confirmDelete()"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors duration-200">
            Hapus Akun
        </button>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-2">Hapus Akun?</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus akun ini? Semua data Anda akan hilang permanen.</p>
        <div class="flex space-x-3">
            <button onclick="closeDeleteModal()"
                    class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 rounded-lg transition-colors duration-200">
                Batal
            </button>
            <form method="POST" action="{{ route('profile.delete') }}" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition-colors duration-200">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview image before upload
    document.getElementById('avatar-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        field.type = field.type === 'password' ? 'text' : 'password';
    }

    // Delete account confirmation
    function confirmDelete() {
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
</script>
@endpush
