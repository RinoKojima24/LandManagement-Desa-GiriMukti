<?php

namespace App\Http\Controllers;

use App\Helpers\WaHelpers;
use App\Models\DikeluarkanSuratUkur;
use App\Models\PendaftaranPeralihan;
use App\Models\PendaftaranPertama;
use App\Models\PetaTanah;
use App\Models\Rt;
use App\Models\SuratPermohonan;
use App\Models\SuratUkur;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;


class TanahController extends Controller
{
public function index(Request $request)
{
    // Jika request AJAX untuk DataTables
    if ($request->ajax()) {
        $query = DB::table('bidang_tanah as bt')
            ->leftJoin('jenis_tanah as jt', 'bt.id_jenis_tanah', '=', 'jt.id_jenis_tanah')
            ->leftJoin('status_kepemilikan as sk', 'bt.id_status_kepemilikan', '=', 'sk.id_status')
            ->leftJoin('wilayah_administratif as wa', 'bt.id_wilayah', '=', 'wa.id_wilayah')
            ->select(
                'bt.id_bidang_tanah',
                'bt.nomor_bidang',
                'bt.nib',
                'bt.luas_tanah',
                'bt.alamat_tanah',
                'bt.koordinat_tanah',
                'bt.batas_utara',
                'bt.batas_selatan',
                'bt.batas_timur',
                'bt.batas_barat',
                'bt.keterangan',
                'bt.is_active',
                'jt.nama_jenis',
                'sk.nama_status',
                'wa.nama_wilayah'
            );


        return DataTables::of($query)
            ->addIndexColumn() // Menambahkan kolom nomor urut
            ->addColumn('luas_formatted', function($row) {
                return number_format($row->luas_tanah, 2) . ' m²';
            })
            ->addColumn('status_badge', function($row) {
                if ($row->is_active == 1) {
                    return '<span class="badge badge-success">Aktif</span>';
                } else {
                    return '<span class="badge badge-danger">Tidak Aktif</span>';
                }
            })
            ->addColumn('koordinat_button', function($row) {
                if ($row->koordinat_tanah) {
                    return '<button class="btn btn-info btn-sm" onclick="showMap(\'' . $row->koordinat_tanah . '\', \'' . addslashes($row->nomor_bidang) . '\', \'' . addslashes($row->alamat_tanah) . '\')">
                                <i class="fas fa-map-marker-alt"></i> Lihat
                            </button>';
                } else {
                    return '<span class="text-muted">-</span>';
                }
            })
            ->addColumn('action', function($row) {
                $buttons = '<div class="btn-group" role="group">';

                // Detail button
                $buttons .= '<a href="' . route('bidang-tanah.show', $row->id_bidang_tanah) . '" class="btn btn-info btn-sm" title="Detail">
                <i class="fas fa-eye"></i>
            </a>';

                // Edit button
                $buttons .= '<button class="btn btn-warning btn-sm" onclick="editData(' . $row->id_bidang_tanah . ')" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>';

                // Delete button
                $buttons .= '<button class="btn btn-danger btn-sm" onclick="deleteData(' . $row->id_bidang_tanah . ')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>';

                $buttons .= '</div>';

                return $buttons;
            })
            ->filterColumn('nama_jenis', function($query, $keyword) {
                $query->where('jt.nama_jenis', 'like', "%{$keyword}%");
            })
            ->filterColumn('nama_status', function($query, $keyword) {
                $query->where('sk.nama_status', 'like', "%{$keyword}%");
            })
            ->filterColumn('nama_wilayah', function($query, $keyword) {
                $query->where('wa.nama_wilayah', 'like', "%{$keyword}%");
            })
            ->rawColumns(['status_badge', 'koordinat_button', 'action'])
            ->make(true);
    }

    // Untuk halaman biasa, kembalikan view tanpa data
    // Data akan di-load via AJAX oleh DataTables
return view('admin.tanah');
}

public function show($id)
{
    try {
        // Query untuk mengambil data bidang tanah berdasarkan ID dengan join ke tabel terkait
        $bidangTanah = DB::table('bidang_tanah as bt')
            ->leftJoin('jenis_tanah as jt', 'bt.id_jenis_tanah', '=', 'jt.id_jenis_tanah')
            ->leftJoin('status_kepemilikan as sk', 'bt.id_status_kepemilikan', '=', 'sk.id_status')
            ->leftJoin('wilayah_administratif as wa', 'bt.id_wilayah', '=', 'wa.id_wilayah')
            ->select(
                'bt.id_bidang_tanah',
                'bt.nomor_bidang',
                'bt.nib',
                'bt.luas_tanah',
                'bt.alamat_tanah',
                'bt.koordinat_tanah',//format koordinat
                'bt.batas_utara',//format koordinat
                'bt.batas_selatan',//format koordinat
                'bt.batas_timur',//format koordinat
                'bt.batas_barat',//format koordinat
                'bt.keterangan',
                'bt.is_active',
                'bt.created_at',
                'bt.updated_at',
                'jt.nama_jenis',
                'jt.deskripsi as deskripsi_jenis',
                'sk.nama_status',
                'sk.deskripsi as deskripsi_status',
                'wa.nama_wilayah',
                'wa.kode_wilayah'
            )
            ->where('bt.id_bidang_tanah', $id)
            ->first();

        // Cek apakah data ditemukan
        if (!$bidangTanah) {
            return response()->json([
                'success' => false,
                'message' => 'Data bidang tanah tidak ditemukan'
            ], 404);
        }


        // Format data untuk response
        $data = [
            'id_bidang_tanah' => $bidangTanah->id_bidang_tanah,
            'nomor_bidang' => $bidangTanah->nomor_bidang ?? '-',
            'nib' => $bidangTanah->nib ?? '-',
            'luas_tanah' => number_format($bidangTanah->luas_tanah, 2),
            'luas_tanah_raw' => $bidangTanah->luas_tanah,
            'alamat_tanah' => $bidangTanah->alamat_tanah ?? '-',
            'koordinat_tanah' => $bidangTanah->koordinat_tanah ?? '-',
            'batas_utara' => $bidangTanah->batas_utara ?? '-',
            'batas_selatan' => $bidangTanah->batas_selatan ?? '-',
            'batas_timur' => $bidangTanah->batas_timur ?? '-',
            'batas_barat' => $bidangTanah->batas_barat ?? '-',
            'keterangan' => $bidangTanah->keterangan ?? '-',
            'is_active' => $bidangTanah->is_active,
            'status_text' => $bidangTanah->is_active == 1 ? 'Aktif' : 'Tidak Aktif',
            'nama_jenis' => $bidangTanah->nama_jenis ?? '-',
            'deskripsi_jenis' => $bidangTanah->deskripsi_jenis ?? '-',
            'nama_status' => $bidangTanah->nama_status ?? '-',
            'deskripsi_status' => $bidangTanah->deskripsi_status ?? '-',
            'nama_wilayah' => $bidangTanah->nama_wilayah ?? '-',
            'kode_wilayah' => $bidangTanah->kode_wilayah ?? '-',
            'created_at' => $bidangTanah->created_at ? date('d/m/Y H:i', strtotime($bidangTanah->created_at)) : '-',
            'updated_at' => $bidangTanah->updated_at ? date('d/m/Y H:i', strtotime($bidangTanah->updated_at)) : '-'
        ];

        // Jika request AJAX (untuk modal detail), return JSON
        if (request()->ajax()) {
            return response()->json($data);
        }
        // Jika bukan AJAX, return view detail (opsional)
        return view('admin.tanah-detail', compact('data'));

    } catch (\Exception $e) {
        // Handle error
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
            log($e->getMessage());
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data' . $e->getMessage());
    }
}

public function data_titik_tanah(){
    try{
     $data = DB::table('bidang_tanah')
    ->select('id_bidang_tanah', 'koordinat_tanah')
    ->whereNotNull('koordinat_tanah')
    ->get()
    ->map(function($item) {
        $coords = explode(',', $item->koordinat_tanah);

        return [
            'id_bidang_tanah' => $item->id_bidang_tanah,
            'latitude'  => isset($coords[0]) ? trim($coords[0]) : null,
            'longitude' => isset($coords[1]) ? trim($coords[1]) : null,
        ];
    })
    ->filter(function($item) {
        return $item['latitude'] !== null && $item['longitude'] !== null;
    })
    ->values();
    if(request()->ajax()){
        return response()->json($data);
    }

    return view('admin.map-titik-tanah', compact('data'));
    }
    catch(\Exception $e){
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengambil data: ' . $e->getMessage()
        ], 500);
    }

}
public function data(Request $request){
    // $data = DB::table('bidang_tanah as bt')
    //     ->leftJoin('jenis_tanah as jt', 'bt.id_jenis_tanah', '=', 'jt.id_jenis_tanah')
    //     ->select([
    //         'bt.id_bidang_tanah',
    //         'bt.nomor_bidang',
    //         'bt.nib',
    //         'bt.luas_tanah',
    //         'jt.nama_jenis',
    //         'bt.koordinat_tanah as panjang',
    //         'bt.alamat_tanah as penerbit',
    //         'bt.alamat_tanah'
    //     ]);

    // // Tambahkan fitur search jika ada parameter search
    // if ($request->has('search') && !empty($request->search)) {
    //     $search = $request->search;
    //     $data->where(function($query) use ($search) {
    //         $query->where('bt.nomor_bidang', 'like', "%{$search}%")
    //               ->orWhere('bt.nib', 'like', "%{$search}%")
    //               ->orWhere('bt.alamat_tanah', 'like', "%{$search}%")
    //               ->orWhere('jt.nama_jenis', 'like', "%{$search}%");
    //     });
    // }

    if(isset($_GET['pemilik'])) {
        if(Auth::user()->role == "warga") {
            if($_GET['pemilik'] != Auth::user()->id) {
                return redirect()->back();
            }

        }

        if($_GET['pemilik'] == 0) {
            $data['data'] = PetaTanah::orderBy('created_at', 'DESC');
        } else {
            $data['data'] = PetaTanah::where('user_id', $_GET['pemilik'])->orderBy('created_at', 'DESC');
        }
        $data['data'] = $data['data']->get();// Perbaikan: assign hasil get() ke variable


    } else {
        if(Auth::user()->role == "warga") {
            return redirect()->to('tanah?pemilik='.Auth::user()->id);
        }
        $data['warga'] = User::where('role','warga')->where('is_active', 0);
        if(isset($_GET['search'])) {
            $data['warga'] = $data['warga']->where('nama_petugas', 'LIKE', "%".$_GET['search']."%");
        }
        $data['warga'] = $data['warga']->get();// Perbaikan: assign hasil get() ke variable


    }




    return view('web.data_peta', $data);
}

    public function create(){
        // $data['permohonans'] = DB::table('surat_permohonan')
        //                     ->join('jenis_surat', 'surat_permohonan.id_jenis_surat', '=', 'jenis_surat.id_jenis_surat')
        //                     ->orderBy('surat_permohonan.created_at', 'DESC')->get();
        // dd($data['permohonans'][0]->id_permohonan);
        // $data['warga'] = User::where('role', 'warga')->get();
        if(Auth::user()->role == "warga") {
            if($_GET['pemilik'] != Auth::user()->id) {
                return redirect()->back();
            }

        }
        $rt = Rt::orderBy('created_at', 'ASC')->get();

        $data['rtList'] = null;
        foreach($rt as $a) {
            $data['rtList'][] = ['nama' => $a->nama, 'file' => asset($a->geojson)];
        }

        // dd($data['rtList']);
        $data['warga'] = User::where('id', $_GET['pemilik'])->first();
        return view('web.peta.tambah', $data);
    }

    public function edit($id){
        // $data['permohonans'] = DB::table('surat_permohonan')
        //                     ->join('jenis_surat', 'surat_permohonan.id_jenis_surat', '=', 'jenis_surat.id_jenis_surat')
        //                     ->orderBy('surat_permohonan.created_at', 'DESC')->get();

        // $data['warga'] = User::where('role', 'warga')->get();
        $data['warga'] = User::where('id', $_GET['pemilik'])->first();
        $rt = Rt::orderBy('created_at', 'ASC')->get();

        $data['rtList'] = null;
        foreach($rt as $a) {
            $data['rtList'][] = ['nama' => $a->nama, 'file' => asset($a->geojson)];
        }
        $data['peta'] = PetaTanah::find($id);
        // dd($data['permohonans'][0]->id_permohonan);
        return view('web.peta.edit', $data);
    }

    public function detail($id) {

        $data['peta'] = PetaTanah::find($id);
        return view('web.peta.detail', $data);
    }

    public function print($id) {
        // Load view 'pdf.invoice' dengan data
        $data['peta'] = PetaTanah::find($id);

        $pdf = Pdf::loadView('web.peta.print', $data);
        $pdf->setPaper('A4', 'portrait');

        // Mengunduh file PDF
        return $pdf->stream("Surat Tanah.pdf");
    }

    public function store(Request $request) {
        // dd("ASD");
            // Validasi request



            // $request->validate([
            //     'user_id' => 'required',
            //     'tanggal_pengukuran' => 'required',
            //     'peruntukan' => 'required',
            //     'status' => 'required',
            //     'panjang' => 'required|numeric',
            //     'lebar' => 'required|numeric',
            //     'luas' => 'required|numeric',
            //     'titik_kordinat' => 'required',
            //     'titik_kordinat_polygon' => 'required',
            //     'foto_peta' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            //     'keperluan' => 'nullable|string|max:255',
            // ]);

            // $jenis_surat = [
            //     'skt' => 'SKT',
            //     'sporadik' => 'SPPF',
            //     'waris' => 'SKWT',
            //     'hibah' => 'SHT',
            //     'jual_beli' => 'SJBT',
            //     'tidak_sengketa' => 'SKTS',
            //     'permohonan' => 'SPP',
            //     'lokasi' => 'SKLT',
            // ];

            // dd($request->titik_kordinat_polygon);

            // $kordinat_array = [];
            // $kordinat_polygon = preg_split('/\r\n|\r|\n/', $request->titik_kordinat_polygon);
            // foreach($kordinat_polygon as $wonhi) {
            //     $kordinat_latlit = explode(',', $wonhi);
            //     $kordinat_array[] = [(float) $kordinat_latlit[1], (float) $kordinat_latlit[0]];
            // }
            // // dd($kordinat_array);

            //     // Struktur GeoJSON
            // $geojson = [
            //     "type" => "FeatureCollection",
            //     "features" => [
            //         [
            //             "type" => "Feature",
            //             "properties" => [
            //                 "name" => $request->peruntukan
            //             ],
            //             "geometry" => [
            //                 "type" => "Polygon",
            //                 "coordinates" => [ $kordinat_array ] // polygon harus array 2D
            //             ]
            //         ]
            //     ]
            // ];

            // =======================
            // 1. POLYGON TANAH
            // =======================
            $kordinat_polygon_array = [];

            if (!empty($request->titik_kordinat_polygon)) {
                $lines = preg_split('/\r\n|\r|\n/', trim($request->titik_kordinat_polygon));

                foreach ($lines as $line) {
                    if (trim($line) === '') continue;

                    [$lat, $lng] = array_map('floatval', explode(',', $line));
                    $kordinat_polygon_array[] = [$lng, $lat]; // GeoJSON: [lng, lat]
                }

                // Tutup polygon jika belum tertutup
                if ($kordinat_polygon_array[0] !== end($kordinat_polygon_array)) {
                    $kordinat_polygon_array[] = $kordinat_polygon_array[0];
                }
            }

            // =======================
            // 2. JALAN
            // =======================
            $kordinat_jalan_array = [];

            if (!empty($request->titik_kordinat_jalan)) {
                $lines = preg_split('/\r\n|\r|\n/', trim($request->titik_kordinat_jalan));

                foreach ($lines as $line) {
                    if (trim($line) === '') continue;

                    [$lat, $lng] = array_map('floatval', explode(',', $line));
                    $kordinat_jalan_array[] = [$lng, $lat]; // GeoJSON: [lng, lat]
                }
            }

            // =======================
            // 3. FEATURE COLLECTION
            // =======================
            $features = [];

            // Feature polygon tanah
            if (!empty($kordinat_polygon_array)) {
                $features[] = [
                    "type" => "Feature",
                    "properties" => [
                        "layer" => "tanah",
                        "name" => $request->peruntukan
                    ],
                    "geometry" => [
                        "type" => "Polygon",
                        "coordinates" => [
                            $kordinat_polygon_array
                        ]
                    ]
                ];
            }

            // Feature jalan (LineString — lebih tepat untuk jalan)
            if (!empty($kordinat_jalan_array)) {
                $features[] = [
                    "type" => "Feature",
                    "properties" => [
                        "layer" => "road",
                        "name" => "jalan"
                    ],
                    "geometry" => [
                        "type" => "LineString",
                        "coordinates" => $kordinat_jalan_array
                    ]
                ];
            }

            // =======================
            // 4. FINAL GEOJSON
            // =======================
            $geojson = [
                "type" => "FeatureCollection",
                "features" => $features
            ];


            // Convert ke JSON
            $json = json_encode($geojson, JSON_PRETTY_PRINT);

            // Simpan file
            $filename = 'tanah_' . time() . '.geojson';
            Storage::disk('public')->put('geojson/' . $filename, $json);

            $path = $request->path();

            // Handle upload Gambar Surat
            if ($request->hasFile('foto_peta')) {
                $gambarFile = $request->file('foto_peta');
                $gambarPath = $gambarFile->store('foto_peta', 'public');
            }

            $imageContent = WaHelpers::renderPNG($kordinat_polygon_array, $kordinat_jalan_array, $request->nama_jalan);
            $fileName = 'peta_' . time() . '.png';

            Storage::disk('public')->put('foto_denah/'.$fileName, $imageContent);


            $peta = PetaTanah::create([
                // 'nomor_bidang' => str_pad(0, 3, '0', STR_PAD_LEFT)."/".date('Y'),
                'user_id' => $request->user_id,

                'skala' => $request->skala,
                'penjelasan' => $request->penjelasan,
                'nama_jalan' => $request->nama_jalan,

                // 'status'=> $request->status,
                // 'panjang' => $request->panjang,
                // 'lebar' => $request->lebar,
                // 'luas' => $request->luas,
                // 'peruntukan'=> $request->peruntukan,
                'foto_denah' => 'foto_denah/'.$fileName,
                'titik_kordinat'=> $request->titik_kordinat,
                'titik_kordinat_polygon'=> 'storage/geojson/'.$filename,
                // 'tanggal_pengukuran'=> $request->tanggal_pengukuran,
                'foto_peta'=> $gambarPath,
            ]);

                $PendaftaranPertamaData = PendaftaranPertama::create([
                    'peta_tanah_id' => $peta->id,
                    'hak' => $request->hak,
                    'nomor' => $request->nomor,
                    'desa_kel' => $request->desa_kel,
                    'tanggal_berakhirnya_hak' => $request->tanggal_berakhirnya_hak,

                    'nib' => $request->nib,
                    'letak_tanah' => $request->letak_tanah,

                    'konversi' => $request->konversi,
                    'pemberian_hak' => $request->pemberian_hak,
                    'pemecahan' => $request->pemecahan,

                    'tgl_konversi' => $request->tgl_konversi,
                    'no_konversi' => $request->no_konversi,

                    'tgl_pemberian_hak' => $request->tgl_pemberian_hak,
                    'no_pemberian_hak' => $request->no_pemberian_hak,

                    'tgl_pemecahan' => $request->tgl_pemecahan,
                    'no_pemecahan' => $request->no_pemecahan,

                    'tgl_surat_ukur' => $request->tgl_surat_ukur,
                    'no_surat_ukur' => $request->no_surat_ukur,
                    'luas_surat_ukur' => $request->luas_surat_ukur,

                    'nama_pemegang_hak' => $request->nama_pemegang_hak,
                    'tanggal_lahir_akta_pendirian' => $request->tanggal_lahir_akta_pendirian,

                    'petunjuk' => $request->petunjuk,
                ]);

                if ($request->sebab) {
                    foreach ($request->sebab as $idx => $value) {
                        PendaftaranPeralihan::create([
                            'peta_tanah_id' => $peta->id,
                            'sebab' => $request->sebab[$idx],
                            'nama' => $request->nama[$idx],
                            'tanda_tangan' => $request->tanda_tangan[$idx] ?? "Mohon diajukan!",
                        ]);
                    }
                }

                $SuratUkur = SuratUkur::create([
                    'peta_tanah_id' => $peta->id,
                    'nomor' => $request->nomor_surat,
                    'provinsi' => $request->provinsi,
                    'kabupaten' => $request->kabupaten,
                    'kecamatan' => $request->kecamatan,
                    'desa' => $request->desa,

                    'peta' => $request->peta,
                    'nomor_peta' => $request->nomor_peta,
                    'lembar' => $request->lembar,
                    'kotak' => $request->kotak,

                    'keadaan_tanah' => $request->keadaan_tanah,
                    'tanda_tanda_batas' => $request->tanda_tanda_batas,
                    'penunjukan_dan_penetapan_batas' => $request->penunjukan_dan_penetapan_batas,
                    // 'tgl_pemecahan' => $request->tgl_pemecahan,
                    'hal_lain_lain' => $request->hal_lain_lain,

                    'tgl_daftar_isian_208' => $request->tgl_daftar_isian_208,
                    'no_daftar_isian_208' => $request->no_daftar_isian_208,
                    'tgl_daftar_isian_302' => $request->tgl_daftar_isian_302,
                    'no_daftar_isian_302' => $request->no_daftar_isian_302,
                    'tgl_daftar_isian_307' => $request->tgl_daftar_isian_307,
                    'no_daftar_isian_307' => $request->no_daftar_isian_307,
                    'tanggal_penomoran_surat_ukur' => $request->tanggal_penomoran_surat_ukur,
                    'nomor_surat_ukur' => $request->nomor_surat_ukur,
                    'nomor_hak' => $request->nomor_hak,

                ]);

                    if ($request->tanggal_surat_ukur) {
                        foreach ($request->tanggal_surat_ukur as $i => $tgl) {
                            DikeluarkanSuratUkur::create([
                                'surat_ukur_id' => $SuratUkur->id,
                                'tanggal'  => $request->tanggal_surat_ukur[$i],
                                'nomor'    => $request->nomor_surat_ukur_all[$i],
                                'luas'     => $request->luas_surat_ukur_all[$i],
                                'nomor_hak'=> $request->nomor_hak_all[$i],
                                'sisa_luas'=> $request->sisa_luas[$i],
                            ]);
                        }
                    }


            // $peta_edit = PetaTanah::find($peta->id);
            // $peta_edit->nomor_bidang = str_pad($peta_edit->id, 3, '0', STR_PAD_LEFT)."/".date('Y');
            // $peta_edit->save();

            DB::commit();

            return redirect()->to('tanah?pemilik='.$request->user_id)->with('success', "Data Tanah telah ditambahkan!");

        try {

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id) {
        // dd($id);
            // Validasi request
            // $request->validate([
            //     'user_id' => 'required',
            //     'tanggal_pengukuran' => 'required',
            //     'peruntukan' => 'required',
            //     'status' => 'required',
            //     'panjang' => 'required|numeric',
            //     'lebar' => 'required|numeric',
            //     'luas' => 'required|numeric',
            //     'titik_kordinat' => 'required',
            //     'titik_kordinat_polygon' => 'required',
            //     'foto_peta' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            //     'keperluan' => 'nullable|string|max:255',
            // ]);

            $jenis_surat = [
                'skt' => 'SKT',
                'sporadik' => 'SPPF',
                'waris' => 'SKWT',
                'hibah' => 'SHT',
                'jual_beli' => 'SJBT',
                'tidak_sengketa' => 'SKTS',
                'permohonan' => 'SPP',
                'lokasi' => 'SKLT',
            ];

            $permohonan = DB::table('surat_permohonan')->join('jenis_surat', 'surat_permohonan.id_jenis_surat', '=', 'jenis_surat.id_jenis_surat')
                          ->where('surat_permohonan.id_permohonan', $request->surat_permohonan_id)->orderBy('surat_permohonan.created_at', 'DESC')->first();
            // dd($permohonan);
            $path = $request->path();

            $peta = PetaTanah::where('id',$id)->first();
            $result = Str::after($peta->titik_kordinat_polygon, 'storage/');
            $file = storage_path('app/public/'.$result);
            // dd($file);
            if (file_exists($file)) {
                unlink($file);
            }

            // Handle upload Gambar Surat

            //   $kordinat_array = [];
            // $kordinat_polygon = preg_split('/\r\n|\r|\n/', $request->titik_kordinat_polygon);
            // foreach($kordinat_polygon as $wonhi) {
            //     $kordinat_latlit = explode(',', $wonhi);
            //     $kordinat_array[] = [(float) $kordinat_latlit[1], (float) $kordinat_latlit[0]];
            // }
            // // dd($kordinat_array);

            //     // Struktur GeoJSON
            // $geojson = [
            //     "type" => "FeatureCollection",
            //     "features" => [
            //         [
            //             "type" => "Feature",
            //             "properties" => [
            //                 "name" => $request->peruntukan
            //             ],
            //             "geometry" => [
            //                 "type" => "Polygon",
            //                 "coordinates" => [ $kordinat_array ] // polygon harus array 2D
            //             ]
            //         ]
            //     ]
            // ];

                        // =======================
            // 1. POLYGON TANAH
            // =======================
            $kordinat_polygon_array = [];

            if (!empty($request->titik_kordinat_polygon)) {
                $lines = preg_split('/\r\n|\r|\n/', trim($request->titik_kordinat_polygon));

                foreach ($lines as $line) {
                    if (trim($line) === '') continue;

                    [$lat, $lng] = array_map('floatval', explode(',', $line));
                    $kordinat_polygon_array[] = [$lng, $lat]; // GeoJSON: [lng, lat]
                }

                // Tutup polygon jika belum tertutup
                if ($kordinat_polygon_array[0] !== end($kordinat_polygon_array)) {
                    $kordinat_polygon_array[] = $kordinat_polygon_array[0];
                }
            }

            // =======================
            // 2. JALAN
            // =======================
            $kordinat_jalan_array = [];

            if (!empty($request->titik_kordinat_jalan)) {
                $lines = preg_split('/\r\n|\r|\n/', trim($request->titik_kordinat_jalan));

                foreach ($lines as $line) {
                    if (trim($line) === '') continue;

                    [$lat, $lng] = array_map('floatval', explode(',', $line));
                    $kordinat_jalan_array[] = [$lng, $lat]; // GeoJSON: [lng, lat]
                }
            }

            // =======================
            // 3. FEATURE COLLECTION
            // =======================
            $features = [];

            // Feature polygon tanah
            if (!empty($kordinat_polygon_array)) {
                $features[] = [
                    "type" => "Feature",
                    "properties" => [
                        "layer" => "tanah",
                        "name" => $request->peruntukan
                    ],
                    "geometry" => [
                        "type" => "Polygon",
                        "coordinates" => [
                            $kordinat_polygon_array
                        ]
                    ]
                ];
            }

            // Feature jalan (LineString — lebih tepat untuk jalan)
            if (!empty($kordinat_jalan_array)) {
                $features[] = [
                    "type" => "Feature",
                    "properties" => [
                        "layer" => "road",
                        "name" => "jalan"
                    ],
                    "geometry" => [
                        "type" => "LineString",
                        "coordinates" => $kordinat_jalan_array
                    ]
                ];
            }

            // =======================
            // 4. FINAL GEOJSON
            // =======================
            $geojson = [
                "type" => "FeatureCollection",
                "features" => $features
            ];


            // Convert ke JSON
            $json = json_encode($geojson, JSON_PRETTY_PRINT);

            // Simpan file
            $filename = 'tanah_' . time() . '.geojson';
            Storage::disk('public')->put('geojson/' . $filename, $json);

            $imageContent = WaHelpers::renderPNG($kordinat_polygon_array, $kordinat_jalan_array, $request->nama_jalan);
            $fileName = 'peta_' . time() . '.png';

            Storage::disk('public')->put('foto_denah/'.$fileName, $imageContent);

            PetaTanah::where('id',$id)->update([
                // 'user_id' => $request->user_id,
                // 'status'=> $request->status,
                // 'panjang' => $request->panjang,
                // 'lebar' => $request->lebar,
                // 'luas' => $request->luas,
                // 'peruntukan'=> $request->peruntukan,
                'titik_kordinat'=> $request->titik_kordinat,
                'titik_kordinat_polygon'=> 'storage/geojson/'.$filename,
                'foto_denah' => 'foto_denah/'.$fileName,

                'skala' => $request->skala,
                'penjelasan' => $request->penjelasan,
                'nama_jalan' => $request->nama_jalan,

                // 'tanggal_pengecekan' => $request->tanggal_pengecekan,
                'alamat' => $request->alamat,

                // 'titik_kordinat_2'=> $request->titik_kordinat_2,
                // 'titik_kordinat_3'=> $request->titik_kordinat_3,
                // 'titik_kordinat_4'=> $request->titik_kordinat_4,

                'tanggal_pengukuran'=> $request->tanggal_pengukuran,
                // 'foto_peta'=> $gambarPath,
            ]);

            // dd("asdas");


            if ($request->hasFile('foto_peta')) {
                // dd("LALA");
                $gambarFile = $request->file('foto_peta');
                $gambarPath = $gambarFile->store('foto_peta', 'public');
                PetaTanah::where('id',$id)->update([
                    'foto_peta'=> $gambarPath,
                ]);
            }

            $PendaftaranPertamaData = PendaftaranPertama::where('peta_tanah_id', $id)->update([
                    'hak' => $request->hak,
                    'nomor' => $request->nomor,
                    'desa_kel' => $request->desa_kel,
                    'tanggal_berakhirnya_hak' => $request->tanggal_berakhirnya_hak,

                    'nib' => $request->nib,
                    'letak_tanah' => $request->letak_tanah,

                    'konversi' => $request->konversi,
                    'pemberian_hak' => $request->pemberian_hak,
                    'pemecahan' => $request->pemecahan,

                    'tgl_konversi' => $request->tgl_konversi,
                    'no_konversi' => $request->no_konversi,

                    'tgl_pemberian_hak' => $request->tgl_pemberian_hak,
                    'no_pemberian_hak' => $request->no_pemberian_hak,

                    'tgl_pemecahan' => $request->tgl_pemecahan,
                    'no_pemecahan' => $request->no_pemecahan,

                    'tgl_surat_ukur' => $request->tgl_surat_ukur,
                    'no_surat_ukur' => $request->no_surat_ukur,
                    'luas_surat_ukur' => $request->luas_surat_ukur,

                    'nama_pemegang_hak' => $request->nama_pemegang_hak,
                    'tanggal_lahir_akta_pendirian' => $request->tanggal_lahir_akta_pendirian,

                    'petunjuk' => $request->petunjuk,
                ]);

                PendaftaranPeralihan::where('peta_tanah_id', $id)->delete();
                if ($request->sebab) {
                    foreach ($request->sebab as $idx => $value) {
                        PendaftaranPeralihan::create([
                            'peta_tanah_id' => $peta->id,
                            'sebab' => $request->sebab[$idx],
                            'nama' => $request->nama[$idx],
                            'tanda_tangan' => $request->tanda_tangan[$idx] ?? "Mohon diajukan!",
                        ]);
                    }
                }

                $SuratUkur = SuratUkur::where('peta_tanah_id', $id)->update([
                    'nomor' => $request->nomor_surat,
                    'provinsi' => $request->provinsi,
                    'kabupaten' => $request->kabupaten,
                    'kecamatan' => $request->kecamatan,
                    'desa' => $request->desa,

                    'peta' => $request->peta,
                    'nomor_peta' => $request->nomor_peta,
                    'lembar' => $request->lembar,
                    'kotak' => $request->kotak,

                    'keadaan_tanah' => $request->keadaan_tanah,
                    'tanda_tanda_batas' => $request->tanda_tanda_batas,
                    'penunjukan_dan_penetapan_batas' => $request->penunjukan_dan_penetapan_batas,
                    // 'tgl_pemecahan' => $request->tgl_pemecahan,
                    'hal_lain_lain' => $request->hal_lain_lain,

                    'tgl_daftar_isian_208' => $request->tgl_daftar_isian_208,
                    'no_daftar_isian_208' => $request->no_daftar_isian_208,
                    'tgl_daftar_isian_302' => $request->tgl_daftar_isian_302,
                    'no_daftar_isian_302' => $request->no_daftar_isian_302,
                    'tgl_daftar_isian_307' => $request->tgl_daftar_isian_307,
                    'no_daftar_isian_307' => $request->no_daftar_isian_307,
                    'tanggal_penomoran_surat_ukur' => $request->tanggal_penomoran_surat_ukur,
                    'nomor_surat_ukur' => $request->nomor_surat_ukur,
                    'nomor_hak' => $request->nomor_hak,

                ]);

                $SuratUkur = SuratUkur::where('peta_tanah_id', $id)->first();

                DikeluarkanSuratUkur::where('surat_ukur_id', $SuratUkur->id)->delete();
                if ($request->tanggal_surat_ukur) {
                    foreach ($request->tanggal_surat_ukur as $i => $tgl) {
                        DikeluarkanSuratUkur::create([
                            'surat_ukur_id' => $SuratUkur->id,
                            'tanggal'  => $request->tanggal_surat_ukur[$i],
                            'nomor'    => $request->nomor_surat_ukur_all[$i],
                            'luas'     => $request->luas_surat_ukur_all[$i],
                            'nomor_hak'=> $request->nomor_hak_all[$i],
                            'sisa_luas'=> $request->sisa_luas[$i],
                        ]);
                    }
                }

            // dd("ASD");


            // return response($imageContent)->header('Content-Type', 'image/png');

            DB::commit();

            return redirect()->to('tanah/'.$id.'/show')->with('success', "Data Tanah telah diubah!");
        try {

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Get data for search/autocomplete
     */
    public function search(Request $request)
    {
        $term = $request->get('term', '');

        $data = DB::table('bidang_tanah as bt')
            ->leftJoin('jenis_tanah as jt', 'bt.id_jenis_tanah', '=', 'jt.id_jenis_tanah')
            ->leftJoin('status_kepemilikan as sk', 'bt.id_status_kepemilikan', '=', 'sk.id_status')
            ->leftJoin('wilayah_administratif as wa', 'bt.id_wilayah', '=', 'wa.id_wilayah')
            ->select(
                'bt.id_bidang_tanah',
                'bt.nomor_bidang',
                'bt.nib',
                'bt.alamat_tanah'
            )
            ->where(function($query) use ($term) {
                $query->where('bt.nomor_bidang', 'like', "%{$term}%")
                      ->orWhere('bt.nib', 'like', "%{$term}%")
                      ->orWhere('bt.alamat_tanah', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get();

        return response()->json($data);
    }

    public function destroy($id) {
        $peta = PetaTanah::find($id);
        $peta->delete();
        return redirect()->to('tanah')->with('success', "Data Tanah telah dihapus!");

    }
}
