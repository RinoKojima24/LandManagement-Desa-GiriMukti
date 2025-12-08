<?php

namespace App\Http\Controllers;

use App\Models\PetaTanah;
use App\Models\SuratPermohonan;
use App\Models\User;
use Illuminate\Http\Request;
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
    $data = DB::table('bidang_tanah as bt')
        ->leftJoin('jenis_tanah as jt', 'bt.id_jenis_tanah', '=', 'jt.id_jenis_tanah')
        ->select([
            'bt.id_bidang_tanah',
            'bt.nomor_bidang',
            'bt.nib',
            'bt.luas_tanah',
            'jt.nama_jenis',
            'bt.koordinat_tanah as panjang',
            'bt.alamat_tanah as penerbit',
            'bt.alamat_tanah'
        ]);

    // Tambahkan fitur search jika ada parameter search
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $data->where(function($query) use ($search) {
            $query->where('bt.nomor_bidang', 'like', "%{$search}%")
                  ->orWhere('bt.nib', 'like', "%{$search}%")
                  ->orWhere('bt.alamat_tanah', 'like', "%{$search}%")
                  ->orWhere('jt.nama_jenis', 'like', "%{$search}%");
        });
    }

    $data = PetaTanah::orderBy('created_at', 'DESC');

    $data = $data->get(); // Perbaikan: assign hasil get() ke variable

    return view('web.data_peta', compact('data'));
}

    public function create(){
        // $data['permohonans'] = DB::table('surat_permohonan')
        //                     ->join('jenis_surat', 'surat_permohonan.id_jenis_surat', '=', 'jenis_surat.id_jenis_surat')
        //                     ->orderBy('surat_permohonan.created_at', 'DESC')->get();
        // dd($data['permohonans'][0]->id_permohonan);
        $data['warga'] = User::where('role', 'warga')->get();
        return view('web.peta.tambah', $data);
    }

    public function edit($id){
        // $data['permohonans'] = DB::table('surat_permohonan')
        //                     ->join('jenis_surat', 'surat_permohonan.id_jenis_surat', '=', 'jenis_surat.id_jenis_surat')
        //                     ->orderBy('surat_permohonan.created_at', 'DESC')->get();

        $data['warga'] = User::where('role', 'warga')->get();
        $data['peta'] = PetaTanah::find($id);
        // dd($data['permohonans'][0]->id_permohonan);
        return view('web.peta.edit', $data);
    }

    public function detail($id) {

        $data['peta'] = PetaTanah::find($id);
        return view('web.peta.detail', $data);
    }

    public function store(Request $request) {
        // dd("ASD");
            // Validasi request
            $request->validate([
                'user_id' => 'required',
                'tanggal_pengukuran' => 'required',
                'peruntukan' => 'required',
                'status' => 'required',
                'panjang' => 'required|numeric',
                'lebar' => 'required|numeric',
                'luas' => 'required|numeric',
                'titik_kordinat' => 'required',
                'titik_kordinat_polygon' => 'required',
                'foto_peta' => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'keperluan' => 'nullable|string|max:255',
            ]);

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

            // dd($request->titik_kordinat_polygon);

            $kordinat_array = [];
            $kordinat_polygon = preg_split('/\r\n|\r|\n/', $request->titik_kordinat_polygon);
            foreach($kordinat_polygon as $wonhi) {
                $kordinat_latlit = explode(',', $wonhi);
                $kordinat_array[] = [(float) $kordinat_latlit[1], (float) $kordinat_latlit[0]];
            }
            // dd($kordinat_array);

                // Struktur GeoJSON
            $geojson = [
                "type" => "FeatureCollection",
                "features" => [
                    [
                        "type" => "Feature",
                        "properties" => [
                            "name" => $request->peruntukan
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
            $filename = 'tanah_' . time() . '.geojson';
            Storage::disk('public')->put('geojson/' . $filename, $json);

            // dd("That Way");

            // $permohonan = DB::table('surat_permohonan')->join('jenis_surat', 'surat_permohonan.id_jenis_surat', '=', 'jenis_surat.id_jenis_surat')
            //               ->where('surat_permohonan.id_permohonan', $request->surat_permohonan_id)->orderBy('surat_permohonan.created_at', 'DESC')->first();
            // dd($permohonan);
            $path = $request->path();

            // Handle upload Gambar Surat
            if ($request->hasFile('foto_peta')) {
                // dd("LALA");
                $gambarFile = $request->file('foto_peta');
                $gambarPath = $gambarFile->store('foto_peta', 'public');
            }


            $peta = PetaTanah::create([
                'nomor_bidang' => str_pad(0, 3, '0', STR_PAD_LEFT)."/".date('Y'),
                'user_id' => $request->user_id,
                'status'=> $request->status,
                'panjang' => $request->panjang,
                'lebar' => $request->lebar,
                'luas' => $request->luas,
                'peruntukan'=> $request->peruntukan,
                'titik_kordinat'=> $request->titik_kordinat,
                'titik_kordinat_polygon'=> 'storage/geojson/'.$filename,
                // 'titik_kordinat_2'=> $request->titik_kordinat_2,
                // 'titik_kordinat_3'=> $request->titik_kordinat_3,
                // 'titik_kordinat_4'=> $request->titik_kordinat_4,

                'tanggal_pengukuran'=> $request->tanggal_pengukuran,
                'foto_peta'=> $gambarPath,
            ]);


            $peta_edit = PetaTanah::find($peta->id);
            $peta_edit->nomor_bidang = str_pad($peta_edit->id, 3, '0', STR_PAD_LEFT)."/".date('Y');
            $peta_edit->save();
            // dd("ASD");



            DB::commit();

            // return redirect()->back()->with('success', "Data Tanah telah ditambahkan!");
            return redirect()->to('tanah')->with('success', "Data Tanah telah ditambahkan!");

        try {

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id) {
        // dd($id);
        try {
            // Validasi request
            $request->validate([
                'user_id' => 'required',
                'tanggal_pengukuran' => 'required',
                'peruntukan' => 'required',
                'status' => 'required',
                'panjang' => 'required|numeric',
                'lebar' => 'required|numeric',
                'luas' => 'required|numeric',
                'titik_kordinat' => 'required',
                'titik_kordinat_polygon' => 'required',
                'foto_peta' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'keperluan' => 'nullable|string|max:255',
            ]);

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

              $kordinat_array = [];
            $kordinat_polygon = preg_split('/\r\n|\r|\n/', $request->titik_kordinat_polygon);
            foreach($kordinat_polygon as $wonhi) {
                $kordinat_latlit = explode(',', $wonhi);
                $kordinat_array[] = [(float) $kordinat_latlit[1], (float) $kordinat_latlit[0]];
            }
            // dd($kordinat_array);

                // Struktur GeoJSON
            $geojson = [
                "type" => "FeatureCollection",
                "features" => [
                    [
                        "type" => "Feature",
                        "properties" => [
                            "name" => $request->peruntukan
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
            $filename = 'tanah_' . time() . '.geojson';
            Storage::disk('public')->put('geojson/' . $filename, $json);


            PetaTanah::where('id',$id)->update([
                'user_id' => $request->user_id,
                'status'=> $request->status,
                'panjang' => $request->panjang,
                'lebar' => $request->lebar,
                'luas' => $request->luas,
                'peruntukan'=> $request->peruntukan,
                'titik_kordinat'=> $request->titik_kordinat,
                'titik_kordinat_polygon'=> 'storage/geojson/'.$filename,
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

            // dd("ASD");



            DB::commit();

            return redirect()->to('tanah/'.$id.'/show')->with('success', "Data Tanah telah diubah!");

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
