@extends('layouts.mobile')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Informasi Layanan</h1>
        <p class="text-gray-600">Temukan informasi terbaru tentang layanan kami</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" action="{{ url()->current() }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-search mr-1"></i> Pencarian
                    </label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari judul atau konten..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-folder mr-1"></i> Kategori
                    </label>
                    <select
                        name="category"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-1"></i> Dari Tanggal
                    </label>
                    <input
                        type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar mr-1"></i> Sampai Tanggal
                    </label>
                    <input
                        type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex flex-wrap gap-2 mt-4">
                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 flex items-center"
                >
                    <i class="fas fa-filter mr-2"></i> Terapkan Filter
                </button>
                <a
                    href="{{ url()->current() }}"
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-300 flex items-center"
                >
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Result Info -->
    <div class="mb-4 text-gray-600">
        <p>Menampilkan <strong>{{ $posts->count() }}</strong> dari <strong>{{ $posts->total() }}</strong> posting</p>
    </div>

    <!-- Posts Grid -->
    @if($posts->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
            @foreach($posts as $post)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                    <!-- Thumbnail -->
                    <div class="relative h-48 bg-gray-200 overflow-hidden">
                        @if($post->thumbnail)
                            <img
                                src="{{ asset('storage/' . $post->thumbnail) }}"
                                alt="{{ $post->title }}"
                                class="w-full h-full object-cover"
                            >
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-400 to-blue-600">
                                <i class="fas fa-image text-white text-5xl opacity-50"></i>
                            </div>
                        @endif

                        <!-- Category Badge -->
                        @if($post->category_name)
                            <span class="absolute top-3 right-3 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                {{ $post->category_name }}
                            </span>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <!-- Date -->
                        <div class="flex items-center text-gray-500 text-sm mb-3">
                            <i class="far fa-calendar mr-2"></i>
                            <span>{{ \Carbon\Carbon::parse($post->published_at ?? $post->created_at)->format('d M Y') }}</span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-lg font-bold text-gray-800 mb-3 line-clamp-2 hover:text-blue-600 transition">
                            {{ $post->title }}
                        </h3>

                        <!-- Excerpt -->
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ Str::limit(strip_tags($post->content), 120) }}
                        </p>

                        <!-- Read More Button -->
                        <a
                            href="{{ route('posts.show', $post->id) }}"
                            class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold text-sm transition"
                        >
                            Baca Selengkapnya
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $posts->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Posting Ditemukan</h3>
            <p class="text-gray-500 mb-4">Coba ubah filter atau kriteria pencarian Anda</p>
            <a
                href="{{ url()->current() }}"
                class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300"
            >
                Reset Filter
            </a>
        </div>
    @endif
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
@endsection
