<?php

namespace App\Http\Controllers;

use App\Helpers\WaHelpers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        try{
            // Filter parameters
            $cari_nama = $request->input('cari_nama') ;
            $status = $request->input('status') ?? 0;
            $role = $request->input('role') ?? 'warga';

            $data['warga'] = User::where('role', $role)->where('is_active', $status)->where('nama_petugas', 'LIKE', "%$cari_nama%")->get();

            return view('web.warga.index', $data);

        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $data['warga'] = User::find($id);

        return view('web.warga.detail', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $user = User::find($id);
        $user->is_active = $request->status;
        $user->save();

        WaHelpers::sendWa($request->no_telepon, $request->pesan);


        return redirect()->back()->with('success', 'Surat berhasil dikirim ke pengaju dan disimpan');
    }

    public function lihatKtp($filename)
    {
        // dd("ASD");
        $path = storage_path('app/private/foto_ktp/' . $filename);
        // dd($path);
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
