@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Daftar Posts</h4>
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Post Baru
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($posts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">Thumbnail</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th style="width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                        <tr>
                                            <td>
                                                @if($post->thumbnail)
                                                    <img src="{{ asset('storage/' . $post->thumbnail) }}"
                                                         alt="{{ $post->title }}"
                                                         class="img-thumbnail"
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('posts.show', $post->id) }}" class="text-decoration-none">
                                                    <strong>{{ $post->title }}</strong>
                                                </a>
                                                <br>
                                                <small class="text-muted">{{ $post->slug }}</small>
                                            </td>
                                            <td>
                                                @if($post->category_name)
                                                    <span class="badge bg-info">{{ $post->category_name }}</span>
                                                @else
                                                    <span class="badge bg-secondary">Tanpa Kategori</span>
                                                @endif
                                            </td>
                                            <td>{{ $post->user_name }}</td>
                                            <td>
                                                @if($post->status == 'published')
                                                    <span class="badge bg-success">Published</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Draft</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ date('d M Y', strtotime($post->created_at)) }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('posts.show', $post->id) }}"
                                                       class="btn btn-sm btn-info"
                                                       title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('posts.edit', $post->id) }}"
                                                       class="btn btn-sm btn-warning"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('posts.destroy', $post->id) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus post ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-danger"
                                                                title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle"></i> Belum ada post.
                            <a href="{{ route('posts.create') }}">Buat post pertama Anda!</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
