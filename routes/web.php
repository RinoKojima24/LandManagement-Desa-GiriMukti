<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AntreanController;
use App\Http\Controllers\KritikSaranController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TanahController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PermohonanSuratController;
use Database\Seeders\NontificationFactory;

// ===========================
// GUEST (Login & Register)
// ===========================
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('Auth.login'))->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login_post');
});

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ===========================
// AUTHENTICATED USERS
// ===========================
Route::middleware(['auth'])->group(function () {

    // ---------- HOME ----------
        Route::get('/', function () {
            $posts = DB::table('posts')
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit('4') // tampilkan 4 posting per halaman
                ->get();

            return view('web.home', compact('posts'));
        })->name('home');


    // ---------- HOME ---------- //
        Route::get('/berkas/user', function () {
    $userId = auth()->id();

    // Ambil data dari kedua tabel dengan filter created_by
    $berkas = DB::table('surat_keterangan')
        ->select(
            'id_permohonan',
            'nik',
            'nama_lengkap',
            'jenis_kelamin',
            'alamat',
            'status',
            'created_at',
            DB::raw("'surat_keterangan' as jenis_surat")
        )
        ->where('created_by', $userId)
        ->unionAll(
            DB::table('surat_permohonan')
                ->select(
                    'id_permohonan',
                    'nik',
                    'nama_lengkap',
                    'jenis_kelamin',
                    'alamat',
                    'status',
                    'created_at',
                    DB::raw("'surat_permohonan' as jenis_surat")
                )
                ->where('created_by', $userId)
        )
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('web.berkas-user', compact('berkas'));
})->name('berkas-user');

    route::get('/info-layanan',[PostController::class,'info_layanan'])->name('info-layanan');

    // ---------- PROFILE ----------
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::put('/profile/update', 'update')->name('profile.update');
        Route::put('/password/update', 'updatePassword')->name('password.update');
        Route::delete('/profile/delete', 'delete')->name('profile.delete');
    });

    // ---------- ANTREAN ----------
    Route::resource('antrean', AntreanController::class);
    Route::prefix('antrean')->controller(AntreanController::class)->group(function () {
        Route::get('{id}/barcode', 'generateBarcode')->name('antrean.barcode');
        Route::get('{id}/tiket', 'downloadTiket')->name('antrean.tiket');
        Route::post('{id}/status', 'updateStatus')->name('antrean.status');
        Route::post('{id}/batal', 'batal')->name('antrean.batal');
        Route::post('scan', 'scanBarcode')->name('antrean.scan');
    });

    // ---------- SURAT ----------
    Route::prefix('surat')->middleware('isAdmin')->controller(PengajuanSuratController::class)->group(function () {
        Route::get('/unverified', 'dataUnverified')->name('surat.unverified');
        Route::get('/detail/{id}/{tipe}', 'detail')->name('surat.detail');
        Route::post('/verified/{id}', 'verified')->name('surat.verified');
        Route::post('/reject/{id}', 'reject')->name('surat.reject');
    });

    // ---------- TANAH ----------
    Route::controller(TanahController::class)->group(function () {
        Route::get('/tanah', 'data')->name('data.peta');
        Route::get('/data-tanah/titik', 'data_titik_tanah')->name('data_titik_tanah');
        Route::get('/data-tanah/{id}', 'show')->name('bidang-tanah.show');
    });

    // ---------- PENGAJUAN SURAT ----------
    Route::controller(PengajuanSuratController::class)->group(function () {
        Route::get('/berkas', 'index')->name('pengajuanSurat.index');
        Route::get('/permohonan', 'create')->name('permohonan_form');
        Route::get('/permohonan/keterangan', 'create')->name('keterangan_form');
        Route::post('/permohonan/post', 'store')->name('permohonan_post');
        Route::post('/permohonan/keterangan/post', 'store')->name('keterangan_post');
    });



    // ---------- KRITIK & SARAN ----------
    Route::resource('kritik_saran', KritikSaranController::class)->except(['show', 'edit', 'update']);

    // ---------- NOTIFICATIONS ----------
    Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
        // Halaman utama & detail
        Route::get('/', 'index')->name('notifications.index');
        Route::get('/{id}', 'show')->name('notifications.show');

        // API Endpoints
        Route::get('/unread', 'unread')->name('notifications.unread');
        Route::get('/count', 'count')->name('notifications.count');
        Route::get('/stats', 'stats')->name('notifications.stats');

        // Actions
        Route::post('/{id}/read', 'markAsRead')->name('notifications.markAsRead');
        Route::post('/read-all', 'markAllAsRead')->name('notifications.markAllAsRead');
        Route::delete('/{id}', 'destroy')->name('notifications.destroy');
        Route::delete('/read/clear', 'deleteRead')->name('notifications.deleteRead');
    });
});
//show BLOG
route::get('/posts/{id}',[PostController::class,'show'])->name('posts.show');


// ===========================
// ADMIN ONLY
// ===========================
Route::middleware(['auth', 'isAdmin'])->group(function () {
    // ---------- Nontification ---------- //
    Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
        Route::get('/broadcast/create', 'createBroadcast')->name('notifications.broadcast.create');
        Route::post('/', 'store')->name('notifications.store');
        Route::post('/broadcast', 'broadcast')->name('notifications.broadcast');
        Route::post('/broadcast/role', 'broadcastByRole')->name('notifications.broadcast.role');
    });
    // ---------- USER MANAGEMENT ---------- //
        Route::resource('users', UserController::class);
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
        Route::get('/users/role', [UserController::class, 'filterByRole'])->name('users.filter');
        Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // ---------- TANAH MANAGEMENT ---------- //
        Route::get('/data-tanah', [TanahController::class,'index'])->name('bidang-tanah.index');
        Route::get('/data-tanah/create', [TanahController::class,'create'])->name('bidang-tanah.create');
    // ----------BLog MANAGEMENT ---------- //
    Route::resource('posts', PostController::class)->except(['show']);
    //------------Surat management -----------//
    Route::prefix('dashboard-permohonan')->name('dashboard-permohonan.')->group(function () {
    Route::get('/', [PermohonanSuratController::class, 'index'])->name('index');
    Route::get('/create', [PermohonanSuratController::class, 'create'])->name('create');
    Route::post('/', [PermohonanSuratController::class, 'store'])->name('store');
    Route::get('/{tipe}/{id}', [PermohonanSuratController::class, 'show'])->name('show');
    Route::get('/{tipe}/{id}/edit', [PermohonanSuratController::class, 'edit'])->name('edit');
    Route::put('/{tipe}/{id}', [PermohonanSuratController::class, 'update'])->name('update');
    Route::delete('/{tipe}/{id}', [PermohonanSuratController::class, 'destroy'])->name('destroy');
    Route::patch('/{tipe}/{id}/status', [PermohonanSuratController::class, 'updateStatus'])->name('updateStatus');
});
});






// ===========================
// AUTH DEFAULT ROUTES
// ===========================
require __DIR__ . '/auth.php';
