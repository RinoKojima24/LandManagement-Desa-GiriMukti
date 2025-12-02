<?php
namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Storage;
    use App\Helpers\NotificationHelper;
use App\Helpers\WaHelpers;
use App\Http\Requests\PengajuanSuratRequest;
use App\Models\SuratKeterangan;
use App\Models\SuratPermohonan;

    class PengajuanSuratController extends Controller
    {
        public function index(Request $request){

        if(!isset($_GET['tipe_surat']) ) {
            return redirect('berkas?tipe_surat=0');
        }
    try{
        // Filter parameters
        $status = $request->input('status');
        $jenis_surat_id = $request->input('jenis_surat');
        $search = $request->input('search');
        $tanggal_dari = $request->input('tanggal_dari');
        $tanggal_sampai = $request->input('tanggal_sampai');
        $jenis_kelamin = $request->input('jenis_kelamin');
        $surat_keterangan = [];
        $surat_permohonan = [];

        // Query Surat Keterangan dengan filter

        if($_GET['tipe_surat'] == "1") {
            $query_keterangan = DB::table('surat_keterangan as sk')
                ->leftJoin('jenis_surat as js', 'js.id_jenis_surat', '=', 'sk.id_jenis_surat')
                ->select([
                    'sk.*',
                    'js.name',
                    'js.jenis_surat',
                    DB::raw("CONCAT('surat ', js.name, ' ', js.jenis_surat) AS jenis_surat_lengkap")
                ]);

            // Apply filters untuk surat keterangan
            if ($status) {
                $query_keterangan->where('sk.status', $status);
            }

            if ($jenis_surat_id) {
                $query_keterangan->where('sk.id_jenis_surat', $jenis_surat_id);
            }

            if ($search) {
                $query_keterangan->where(function($q) use ($search) {
                    $q->where('sk.nik', 'like', "%{$search}%")
                    ->orWhere('sk.nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('sk.keperluan', 'like', "%{$search}%");
                });
            }

            if ($tanggal_dari) {
                $query_keterangan->whereDate('sk.created_at', '>=', $tanggal_dari);
            }

            if ($tanggal_sampai) {
                $query_keterangan->whereDate('sk.created_at', '<=', $tanggal_sampai);
            }

            if ($jenis_kelamin) {
                $query_keterangan->where('sk.jenis_kelamin', $jenis_kelamin);
            }

            $surat_keterangan = $query_keterangan->orderBy('sk.created_at', 'desc')->get();
        }

        if($_GET['tipe_surat'] == "0") {
            // Query Surat Permohonan dengan filter
            $query_permohonan = DB::table('surat_permohonan as sp')
                ->leftJoin('jenis_surat as js', 'js.id_jenis_surat', '=', 'sp.id_jenis_surat')
                ->select([
                    'sp.*',
                    'js.name',
                    'js.jenis_surat',
                    DB::raw("CONCAT('surat ', js.jenis_surat, ' ', js.name) AS jenis_surat_lengkap")
                ]);

            // Apply filters untuk surat permohonan
            if ($status) {
                $query_permohonan->where('sp.status', $status);
            }

            if ($jenis_surat_id) {
                $query_permohonan->where('sp.id_jenis_surat', $jenis_surat_id);
            }

            if ($search) {
                $query_permohonan->where(function($q) use ($search) {
                    $q->where('sp.nik', 'like', "%{$search}%")
                    ->orWhere('sp.nama_lengkap', 'like', "%{$search}%");
                });
            }

            if ($tanggal_dari) {
                $query_permohonan->whereDate('sp.created_at', '>=', $tanggal_dari);
            }

            if ($tanggal_sampai) {
                $query_permohonan->whereDate('sp.created_at', '<=', $tanggal_sampai);
            }

            if ($jenis_kelamin) {
                $query_permohonan->where('sp.jenis_kelamin', $jenis_kelamin);
            }

            $surat_permohonan = $query_permohonan->orderBy('sp.created_at', 'desc')->get();

        }

        // Get jenis surat untuk dropdown filter
        $jenis_surat_list = DB::table('jenis_surat')->get();

        // dd(count($surat_permohonan));

        return view('web.berkas', compact(
            'surat_keterangan',
            'surat_permohonan',
            'jenis_surat_list'
        ));

    } catch(\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

public function edit($id) {
    if($_GET['tipe_surat'] == "1") {
        $data['query'] = SuratKeterangan::where('id_permohonan', $id)->first();
    } else {
        $data['query'] = SuratPermohonan::where('id_permohonan', $id)->first();

    }

    // dd($data);
    return view('web.berkas.detail', $data);
}


public function update(Request $request, $id) {
    if($_GET['tipe_surat'] == "1") {
        $data['query'] = SuratKeterangan::where('id_permohonan', $id)->update([
            'status' => $request->status
        ]);

        // dd( $request->status);

    } else {
        $test = SuratPermohonan::where('id_permohonan', $id)->update([
            'status' => $request->status
        ]);

    }

    WaHelpers::sendWa($request->no_wa, $request->pesan);


    return redirect()->back()->with('success', 'Surat berhasil dikirim ke pengaju dan disimpan');

}
        /**
         * Store the newly created resource in storage.
         */
public function store(Request $request)
{
    DB::beginTransaction();

    try {
        // Validasi request
        $request->validate([
            'nik' => 'required|string|max:16',
            'namaLengkap' => 'required|string|max:100',
            'jenisKelamin' => 'required|in:L,P',
            'alamat' => 'required|string|max:244',
            'jenis_surat' => 'required|string',
            'ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_pendukung' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keperluan' => 'nullable|string|max:255',
        ]);

        $path = $request->path();
            $jenisSuratType = 'permohonan'; // default

            if ($path === 'permohonan/keterangan/post') {
                $jenisSuratType = 'keterangan';
            }

        // Data yang sama untuk kedua tabel
        $commonData = [
            'nik' => $request->nik,
            'nama_lengkap' => $request->namaLengkap,
            'jenis_kelamin' => $request->jenisKelamin,
            'alamat' => $request->alamat,
            'created_by' => auth()->id(),
        ];

        // Handle upload file KTP
        if ($request->hasFile('ktp')) {
            $ktpFile = $request->file('ktp');
            $ktpPath = $ktpFile->store('ktp', 'public');
            $commonData['ktp'] = $ktpPath;
        }

        // Handle upload file Dokumen Pendukung
        if ($request->hasFile('dokumen_pendukung')) {
            $dokumenFile = $request->file('dokumen_pendukung');
            $dokumenPath = $dokumenFile->store('dokumen_pendukung', 'public');
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
        if ($jenisSurat->jenis_surat === 'keterangan') {
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
            ]);
            NotificationHelper::newLetterRequest(auth()->id(),$idPermohonan,'keterangan');

        } elseif ($jenisSurat->jenis_surat === 'permohonan') {
            // Logika untuk surat permohonan
            $tipe = 'Surat Permohonan';
             $commonData['id_jenis_surat'] = $jenisSurat->id_jenis_surat;
            // Simpan ke tabel surat_permohonan
            $idPermohonan = DB::table('surat_permohonan')->insertGetId($commonData);
            NotificationHelper::newLetterRequest(auth()->id(),$idPermohonan,'surat tanah');

        } else {
            throw new \Exception('Jenis surat tidak valid');
        }

        DB::commit();

        return redirect()->route('home')->with('success', "{$tipe} berhasil dikirim dengan ID: {$idPermohonan}");

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->route('home')->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
    }
}

     public function create(Request $request)
    {
        // dd("SDASD");
        // Cek URL agar tahu route mana yang digunakan
        if ($request->is('permohonan/keterangan')) {
            $route = route('keterangan_post');
            $jenis = 'keterangan';
        } else {
            $route = route('permohonan_post');
            $jenis = 'permohonan';
        }

        // Kirim variabel ke view
        return view('web.pengajuan_surat', compact('route', 'jenis'));
    }

            public function dataUnverified()
        {
            // Data dari surat permohonan
            $permohonan = DB::table('surat_permohonan as sp')
                ->leftJoin('jenis_surat as js', 'js.id_jenis_surat', '=', 'sp.id_jenis_surat')
                ->select([
                    'sp.id_permohonan as id',
                    'sp.nik',
                    'sp.nama_lengkap',
                    'sp.status',
                    'sp.created_at',
                    'js.name as jenis_surat',
                    DB::raw("'permohonan' as tipe_surat") // untuk identifikasi sumber tabel
                ])
                ->where('sp.status', 'pending');

            // Data dari surat keterangan (gunakan union untuk gabung hasil)
            $data = DB::table('surat_keterangan as sk')
                ->leftJoin('jenis_surat as js', 'js.id_jenis_surat', '=', 'sk.id_jenis_surat')
                ->select([
                    'sk.id_permohonan as id',
                    'sk.nik',
                    'sk.nama_lengkap',
                    'sk.status',
                    'sk.created_at',
                    'js.name as jenis_surat',
                    DB::raw("'keterangan' as tipe_surat") // untuk identifikasi sumber tabel
                ])
                ->where('sk.status', 'pending')
                ->union($permohonan)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('web.validasi.validasi_surat', compact('data'));
        }

        public function verified($id, Request $request)
        {
            DB::beginTransaction();

            try {
                // Validasi tipe surat harus dikirim dari form
                $request->validate([
                    'tipe_surat' => 'required|in:permohonan,keterangan'
                ]);

                $tipeSurat = $request->tipe_surat;

                // Update status berdasarkan tipe surat
                if ($tipeSurat === 'permohonan') {
                $userID = DB::table('surat_permohonan')
                    ->where('id_permohonan', $id)
                    ->value('created_by');

                    $updated = DB::table('surat_permohonan')
                        ->where('id_permohonan', $id)
                        ->update([
                            'status' => 'verifikasi',
                            'updated_at' => now()
                        ]);
                NotificationHelper::requestStatusChanged($userID,$id,'selesai');

                } elseif ($tipeSurat === 'keterangan') {
                $userID = DB::table('surat_keterangan')
                    ->where('id_permohonan', $id)
                    ->value('created_by');

                    $updated = DB::table('surat_keterangan')
                        ->where('id_permohonan', $id)
                        ->update([
                            'status' => 'verifikasi',
                            'updated_at' => now()
                        ]);
                 NotificationHelper::requestStatusChanged($userID,$id,'selesai');
                }

                // Cek apakah data berhasil diupdate
                if (!$updated) {
                    throw new \Exception('Data tidak ditemukan atau sudah diverifikasi');
                }

                DB::commit();

                return redirect()->back()->with('success', 'Surat berhasil diverifikasi');

            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal verifikasi surat: ' . $e->getMessage());
            }
        }

        public function reject($id, Request $request)
        {
            DB::beginTransaction();

            try {
                // Validasi input
                $request->validate([
                    'tipe_surat' => 'required|in:permohonan,keterangan',
                ]);

                $tipeSurat = $request->tipe_surat;
                $alasanPenolakan = $request->alasan_penolakan ?? 'Tidak memenuhi syarat';

                // Update status menjadi rejected
                if ($tipeSurat === 'permohonan') {
                $userID = DB::tables('surat_permohonan')->select('created_by')->where('id_permohonan', $id);
                    $updated = DB::table('surat_permohonan')
                        ->where('id_permohonan', $id)
                        ->update([
                            'status' => 'reject',
                            'updated_at' => now()
                        ]);
                NotificationHelper::requestStatusChanged($userID,$id,'reject');

                } elseif ($tipeSurat === 'keterangan') {
                $userID = DB::tables('surat_keterangan')->select('created_by')->where('id_permohonan', $id);
                    $updated = DB::table('surat_keterangan')
                        ->where('id_permohonan', $id)
                        ->update([
                            'status' => 'reject',
                            'updated_at' => now()
                        ]);
                NotificationHelper::requestStatusChanged($userID,$id,'reject');
                }

                if (!$updated) {
                    throw new \Exception('Data tidak ditemukan');
                }

                DB::commit();

                return redirect()->back()->with('success', 'Surat berhasil ditolak');

            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal menolak surat: ' . $e->getMessage());
            }
        }

        public function detail($id, $tipe)
        {
            try {
                if ($tipe === 'permohonan') {
                    $data = DB::table('surat_permohonan as sp')
                        ->leftJoin('jenis_surat as js', 'js.id_jenis_surat', '=', 'sp.id_jenis_surat')
                        ->select([
                            'sp.*',
                            'js.name as jenis_surat',
                            DB::raw("'permohonan' as tipe_surat")
                        ])
                        ->where('sp.id_permohonan', $id)
                        ->first();

                } elseif ($tipe === 'keterangan') {
                    $data = DB::table('surat_keterangan as sk')
                        ->leftJoin('jenis_surat as js', 'js.id_jenis_surat', '=', 'sk.id_jenis_surat')
                        ->select([
                            'sk.*',
                            'js.name as jenis_surat',
                            DB::raw("'keterangan' as tipe_surat")
                        ])
                        ->where('sk.id_permohonan', $id)
                        ->first();
                } else {
                    return redirect()->back()->with('error', 'Tipe surat tidak valid');
                }

                if (!$data) {
                    return redirect()->back()->with('error', 'Data tidak ditemukan');
                }

                return view('web.validasi.detail_surat', compact('data'));

            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengambil detail: ' . $e->getMessage());
            }
        }
    }
