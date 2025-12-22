<?php

use App\Helpers\NotificationHelper;
use App\Helpers\WaHelpers;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AntreanController;
use App\Http\Controllers\KritikSaranController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\PengajuanSuratController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TanahController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PermohonanSuratController;
use App\Http\Controllers\WargaController;
use App\Models\PetaTanah;
use App\Models\SuratPermohonan;
use App\Models\User;
use Database\Seeders\NontificationFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

// ===========================
// GUEST (Login & Register)
// ===========================

Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('Auth.login'))->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login_post');

    Route::get('/register', [AuthenticatedSessionController::class, 'register']);
    Route::post('/register/send-otp', [AuthenticatedSessionController::class, 'sendOtp']);
    Route::post('/register/check-otp', [AuthenticatedSessionController::class, 'checkOtp']);

    Route::get("/show_geojson", function() {
        $json = file_get_contents("geojson_test.geojson");

        $data = json_decode($json, true);

        foreach($data['features'] as $index => $a) {
            $kordinat_array = [];


            foreach($a['geometry']['coordinates'][0] as $b) {
                $kordinat_array[] = [(float) $b[0], (float) $b[1]];

            }
            // dd($kordinat_array);

                // Struktur GeoJSON
            $geojson = [
                "type" => "FeatureCollection",
                "features" => [
                    [
                        "type" => "Feature",
                        "properties" => [
                            "name" => $a['properties']['Wilayah_RT']
                        ],
                        "geometry" => [
                            "type" => "Polygon",
                            "coordinates" => [ $kordinat_array ] // polygon harus array 2D
                        ]
                    ]
                ]
            ];


            // Convert ke JSON
            $json = json_encode($geojson, JSON_PRETTY_PRINT);

            // Simpan file
            $filename = 'tanah_' . uniqid() . '.geojson';
            Storage::disk('public')->put('geojson/' . $filename, $json);



            PetaTanah::create([
                'nomor_bidang' => "SPFF/".$index."/".date('Y'),
                'status'=> "-",
                'panjang' => 0,
                'lebar' => 0,
                'luas' => $a['properties']['Luas'],
                'peruntukan'=> $a['properties']['Wilayah_RT'],
                'titik_kordinat'=> "-",
                'titik_kordinat_polygon'=> 'storage/geojson/'.$filename,
                'tanggal_pengukuran'=> date('Y-m-d'),
                'foto_peta'=> "-",
            ]);
        }


        // dd($data);
        dd("Done");
    });

    // Route::get('/', function () {
    //     return view('guest.home');
    // })->name('guest.home');

    Route::get('/', fn() => view('Auth.login'))->name('guest.home');
    Route::get('/test', function() {
        $user = User::create([
            'email' => "Nom@email.com",
            'nama_petugas' => "ASD",
            'no_telepon' => "12312",
            'nik' => "11111",
            'foto_ktp' => "ASd",

            'role' => 'warga',
            'is_active' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        dd($user);
        // $pesan = "Ayam\nsapi\nkambing";
        // WaHelpers::sendWa('081212379429', $pesan);
    });

});
    Route::get('/offline', function () {
    return view('offline');
    });
// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// ===========================
// AUTHENTICATED USERS
// ===========================
Route::middleware(['auth'])->group(function () {

    Route::get('/pengajuan_surat', function() {
        // Kirim variabel ke view
        return view('guest.pengajuan_surat');
    });

    Route::get('/pengajuan_keterangan', function() {
        return view('guest.pengajuan_keterangan');
    });



    Route::post('/pengajuan', function(Request $request) {
            // Validasi request
            // $request->validate([
            //     'nik' => 'required|string|max:16',
            //     'namaLengkap' => 'required|string|max:100',
            //     'jenisKelamin' => 'required|in:L,P',
            //     'alamat' => 'required|string|max:244',
            //     'jenis_surat' => 'required|string',
            //     'ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            //     'dokumen_pendukung' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            //     'keperluan' => 'nullable|string|max:255',
            //     'no_wa' => 'required',
            // ]);

            $path = $request->path();

            if($request->type == "keterangan") {
                $jenisSuratType = 'keterangan';
            } else {
                $jenisSuratType = 'permohonan'; // default

            }


                // if ($path === 'permohonan/keterangan/post') {
                // }

            // Data yang sama untuk kedua tabel
            $commonData = [
                'nik' => $request->nik,
                'nama_lengkap' => $request->namaLengkap,
                'jenis_kelamin' => $request->jenisKelamin,
                'alamat' => $request->alamat,
                'no_wa' => $request->no_wa,
                'created_at' => now(),
            ];


            // Handle upload file KTP
            if ($request->hasFile('ktp')) {
                $ktpFile = $request->file('ktp');
                $ktpPath = $ktpFile->store('ktp');
                $commonData['ktp'] = $ktpPath;
            }

            // Handle upload file Dokumen Pendukung
            if ($request->hasFile('dokumen_pendukung')) {
                $dokumenFile = $request->file('dokumen_pendukung');
                $dokumenPath = $dokumenFile->store('dokumen_pendukung');
                $commonData['dokumen_pendukung'] = $dokumenPath;
            }

            // Handle upload Gambar Surat
            if ($request->hasFile('gambar_surat')) {
                $gambarFile = $request->file('gambar_surat');
                $gambarPath = $gambarFile->store('gambar_surat', 'public');
                $commonData['gambar_surat'] = $gambarPath;
            }



            // Tentukan jenis surat berdasarkan input
            $jenisSuratCode = $request->jenis_surat; // 'skt' atau 'spm'

            // Query ke tabel jenis_surat untuk mendapatkan data jenis surat
            $jenisSurat = DB::table('jenis_surat')
                ->where('kode_jenis', $jenisSuratCode)
                ->first();

            // dd($);

            // Jika jenis surat tidak ditemukan, buat data baru
            if (!$jenisSurat) {
                $idJenisSurat = DB::table('jenis_surat')->insertGetId([
                    'jenis_surat' => $jenisSuratType, // ambil berdasarkan jenis route
                    'name' => $jenisSuratCode,
                    'kode_jenis' => $jenisSuratCode,
                    'created_at' => now(),
                ]);

                // Ambil data yang baru dibuat
                $jenisSurat = DB::table('jenis_surat')
                    ->where('id_jenis_surat', $idJenisSurat)
                    ->first();
            }

            // Bedakan logika berdasarkan jenis_surat dari tabel jenis_surat
            if ($jenisSuratType === 'keterangan') {
                // Logika untuk surat keterangan
                $tipe = 'Surat Keterangan';

                // Tambahkan field khusus surat keterangan
                $commonData['keperluan'] = $request->keperluan ?? '-';
                $commonData['id_jenis_surat'] = $jenisSurat->id_jenis_surat;

                // Simpan ke tabel surat_keterangan
                $idPermohonan = DB::table('surat_keterangan')->insertGetId([
                    'nik' => $commonData['nik'],
                    'nama_lengkap' => $commonData['nama_lengkap'],
                    'jenis_kelamin' => $commonData['jenis_kelamin'],
                    'alamat' => $commonData['alamat'],
                    'keperluan' => $commonData['keperluan'] ?? '-',
                    'id_jenis_surat' => $commonData['id_jenis_surat'],
                    'no_wa' => $commonData['no_wa'],
                    'ktp' => $commonData['ktp'],
                    'dokumen_pendukung' => $commonData['dokumen_pendukung'],
                    'created_at' => now(),

                ]);


                $pesan = "Pengajuan Surat Keterangan : \nNIK : {$commonData['nik']}\nNama Lengkap : {$commonData['nama_lengkap']}\nJenis Surat : {$jenisSurat->name}\nKeperluan : {$commonData['keperluan']}\nNomor Urut : {$idPermohonan}";
                WaHelpers::sendWa($commonData['no_wa'], $pesan);

                // $jenisSurat


                // NotificationHelper::newLetterRequest(auth()->id(),$idPermohonan,'keterangan');

            } elseif ($jenisSuratType === 'permohonan') {
                // Logika untuk surat permohonan
                $tipe = 'Surat Permohonan';
                // $commonData['id_jenis_surat'] = $jenisSurat->id_jenis_surat;
                // Simpan ke tabel surat_permohonan
                // $idPermohonan = DB::table('surat_permohonan')->insertGetId($commonData);

                $idPermohonan = SuratPermohonan::create([
                    'user_id' => Auth::user()->id,
                    'nama' => $request->nama,
                    'nik' => $request->nik,
                    'tempat' => $request->tempat,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'pekerjaan' => $request->pekerjaan,
                    'agama' => $request->agama,
                    'alamat' => $request->alamat,
                    'rt_rw' => $request->rt_rw,
                    'jalan' => $request->jalan,


                    'panjang' => $request->panjang,
                    'lebar' => $request->lebar,
                    'luas' => $request->luas,

                    'kondisi_fisik' => $request->panjang,
                    'dasar_perolehan' => $request->lebar,

                    'sebelah_utara' => $request->sebelah_utara,
                    'sebelah_timur' => $request->sebelah_timur,

                    'sebelah_selatan' => $request->sebelah_selatan,
                    'sebelah_barat' => $request->sebelah_barat,

                    'tahun_dikuasai' => $request->tahun_dikuasai,

                    'ktp' => $commonData['ktp'],
                    'dokumen_pendukung' => $commonData['dokumen_pendukung'],

                ]);



                $pesan = "Pengajuan Surat Tanah : \nNIK : {$request->nik}\nNama Lengkap : {$request->nama}\nJenis Surat : {$jenisSurat->name}\nNomor Urut : {$idPermohonan}";
                WaHelpers::sendWa(Auth::user()->no_telepon, $pesan);
                // NotificationHelper::newLetterRequest(auth()->id(),$idPermohonan,'surat tanah');

            } else {
                throw new \Exception('Jenis surat tidak valid');
            }

            DB::commit();

            return redirect()->back()->with('success', "{$tipe} berhasil dikirim dengan ID: {$idPermohonan->id}");
        try {

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    });
    // ---------- HOME ----------
        Route::get('/home', function () {
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

    // ----------- WARGA -----------
    Route::resource('warga',WargaController::class);
    Route::resource('operator',OperatorController::class);

    Route::get('/lihat-ktp/{filename}', [WargaController::class, 'lihatKtp']);

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


        Route::post('/simpan-gambar', function (Request $request) {

            $img = str_replace('data:image/png;base64,', '', $request->image);
            $img = str_replace(' ', '+', $img);
            $img = base64_decode($img);

            // simpan ke storage/app/private
            Storage::put('foto_peta/peta.png', $img);

            return response()->json(['status' => 'ok']);
        });
    // ---------- TANAH ----------
    Route::controller(TanahController::class)->group(function () {
        Route::get('/tanah', 'data')->name('data.peta');
        Route::post('/tanah', 'store')->name('data.peta.store');
        Route::get('/tanah/{id}/show', 'detail')->name('data.peta.detail');
        Route::get('/tanah/{id}/edit', 'edit')->name('data.peta.edit');
        Route::get('/tanah/{id}/print', 'print')->name('data.peta.edit');



        Route::put('/tanah/{id}', 'update')->name('data.peta.update');
        Route::delete('/tanah/{id}', 'destroy')->name('data.peta.destroy');



        Route::get('/tanah/create', 'create')->name('data.peta.tambah');
        Route::get('/data-tanah/titik', 'data_titik_tanah')->name('data_titik_tanah');
        Route::get('/data-tanah/{id}', 'show')->name('bidang-tanah.show');
    });

    // ---------- PENGAJUAN SURAT ----------
    Route::controller(PengajuanSuratController::class)->group(function () {
        Route::get('/berkas', 'index')->name('pengajuanSurat.index');
        Route::get('/berkas/{id}', 'edit')->name('pengajuanSurat.edit');
        Route::put('/berkas/{id}', 'update')->name('pengajuanSurat.update');
        Route::get('/berkas/{id}/print', 'print');
        Route::get('/berkass/file', 'lihatFile');
        Route::get('/history_chat/{id}', 'history');







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
