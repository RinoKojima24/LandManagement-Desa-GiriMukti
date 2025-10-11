<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    $data = $data->get(); // Perbaikan: assign hasil get() ke variable

    return view('web.data_peta', compact('data'));
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
}
