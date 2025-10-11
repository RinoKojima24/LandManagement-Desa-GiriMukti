<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        $posts = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->leftJoin('categories', 'posts.category_id', '=', 'categories.id')
            ->select(
                'posts.*',
                'users.nama_petugas as user_name',
                'categories.name as category_name'
            )
            ->orderBy('posts.created_at', 'desc')
            ->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        $categories = DB::table('categories')->get();
        return view('admin.posts.create', compact('categories'));
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $slug = Str::slug($request->title);

        // Ensure unique slug
        $count = 1;
        $originalSlug = $slug;
        while (DB::table('posts')->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $data = [
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'thumbnail' => $thumbnailPath,
            'status' => $request->status,
            'published_at' => $request->status === 'published' ? now() : null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('posts')->insert($data);

        return redirect()->route('posts.index')
            ->with('success', 'Post berhasil dibuat!');
    }

    /**
     * Display the specified post.
     */
    public function show($id)
    {

        $post = DB::table('posts')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->leftJoin('categories', 'posts.category_id', '=', 'categories.id')
            ->select(
                'posts.*',
                'users.nama_petugas as user_name',
                'categories.name as category_name'
            )
            ->where('posts.id', $id)
            ->first();

        if (!$post) {
            abort(404);
        }
        
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit($id)
    {
        $post = DB::table('posts')->where('id', $id)->first();

        if (!$post) {
            abort(404);
        }

        $categories = DB::table('categories')->get();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $post = DB::table('posts')->where('id', $id)->first();

        if (!$post) {
            abort(404);
        }

        $slug = Str::slug($request->title);

        // Ensure unique slug (exclude current post)
        $count = 1;
        $originalSlug = $slug;
        while (DB::table('posts')->where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $thumbnailPath = $post->thumbnail;
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $data = [
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'thumbnail' => $thumbnailPath,
            'status' => $request->status,
            'updated_at' => now(),
        ];

        // Update published_at only if status changes to published
        if ($request->status === 'published' && $post->status === 'draft') {
            $data['published_at'] = now();
        }

        DB::table('posts')->where('id', $id)->update($data);

        return redirect()->route('posts.index')
            ->with('success', 'Post berhasil diupdate!');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy($id)
    {
        $post = DB::table('posts')->where('id', $id)->first();

        if (!$post) {
            abort(404);
        }

        // Delete thumbnail if exists
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        DB::table('posts')->where('id', $id)->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post berhasil dihapus!');
    }

    public function info_layanan(Request $request)
{
    $query = DB::table('posts')
        ->leftJoin('categories', 'posts.category_id', '=', 'categories.id')
        ->select('posts.*', 'categories.name as category_name', 'categories.slug as category_slug')
        ->where('posts.status', 'published');

    // Filter berdasarkan kategori
    if ($request->filled('category')) {
        $query->where('posts.category_id', $request->category);
    }

    // Filter berdasarkan pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('posts.title', 'like', "%{$search}%")
              ->orWhere('posts.content', 'like', "%{$search}%");
        });
    }

    // Filter berdasarkan tanggal
    if ($request->filled('date_from')) {
        $query->whereDate('posts.published_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('posts.published_at', '<=', $request->date_to);
    }

    $posts = $query->orderBy('posts.created_at', 'desc')
                   ->paginate(4)
                   ->withQueryString(); // mempertahankan parameter filter di pagination

    // Ambil semua kategori untuk dropdown filter
    $categories = DB::table('categories')
        ->orderBy('name', 'asc')
        ->get();

    return view('web.info-layanan', compact('posts', 'categories'));
}
}
