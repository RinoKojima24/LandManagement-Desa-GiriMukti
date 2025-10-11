@extends(Auth::user() && Auth::user()->role === 'admin' ? 'layouts.app' : 'layouts.mobile')

@section('title', 'Detail Post')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Detail Post</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">Posts</a></li>
        <li class="breadcrumb-item active">{{ $post->title }}</li>
    </ol>

    <div class="card mb-4">
@can('isAdmin')

        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-file-alt me-1"></i>
                Informasi Post
            </div>
            <div>
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus post ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
@endcan
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h2 class="mb-3">{{ $post->title }}</h2>

                    @if ($post->thumbnail)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $post->thumbnail) }}"
                                 alt="{{ $post->title }}"
                                 class="img-fluid rounded"
                                 style="max-height: 400px; width: 100%; object-fit: cover;">
                        </div>
                    @endif

                    <div class="post-content">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <strong>Informasi Meta</strong>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted small">Status</label>
                                <div>
                                    @if ($post->status === 'published')
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-secondary">Draft</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small">Penulis</label>
                                <div>{{ $post->user_name }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small">Kategori</label>
                                <div>
                                    @if ($post->category_name)
                                        <span class="badge bg-info">{{ $post->category_name }}</span>
                                    @else
                                        <span class="text-muted">Tidak ada kategori</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small">Slug</label>
                                <div><code>{{ $post->slug }}</code></div>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small">Dibuat</label>
                                <div>{{ date('d M Y, H:i', strtotime($post->created_at)) }}</div>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small">Diupdate</label>
                                <div>{{ date('d M Y, H:i', strtotime($post->updated_at)) }}</div>
                            </div>

                            @if ($post->published_at)
                                <div class="mb-3">
                                    <label class="text-muted small">Dipublikasikan</label>
                                    <div>{{ date('d M Y, H:i', strtotime($post->published_at)) }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Post
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
