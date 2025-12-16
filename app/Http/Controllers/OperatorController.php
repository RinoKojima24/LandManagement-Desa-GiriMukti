<?php

namespace App\Http\Controllers;

use App\Helpers\WaHelpers;
use App\Models\User;
use Illuminate\Http\Request;

class OperatorController extends Controller
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
            $role = $request->input('role') ?? 'operator';

            $data['warga'] = User::where('role', $role)->where('is_active', $status)->where('nama_petugas', 'LIKE', "%$cari_nama%")->get();

            return view('web.operator.index', $data);

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
        return view('web.operator.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        if ($request->hasFile('foto_ktp')) {
            $gambarFile = $request->file('foto_ktp');
            $gambarPath = $gambarFile->store('foto_ktp');
        }
        $user = User::create([
            'email' => $request->email,
            'nama_petugas' => $request->namaLengkap,
            'no_telepon' => $request->no_telepon,
            'nik' => $request->nik,
            'foto_ktp' => $gambarPath,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,

            'role' => 'operator',
            'is_active' => "0",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->to('operator')->with('success', "Data berhasil disimpan!");

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
        $data['operator'] = User::find($id);

        return view('web.operator.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {


        $user = User::where('id', $id)->update([
            'email' => $request->email,
            'nama_petugas' => $request->namaLengkap,
            'no_telepon' => $request->no_telepon,
            'nik' => $request->nik,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,

            'role' => 'operator',
            'is_active' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->hasFile('foto_ktp')) {
            $gambarFile = $request->file('foto_ktp');
            $gambarPath = $gambarFile->store('foto_ktp');
            $user = User::where('id', $id)->update([
                'foto_ktp' => $gambarPath,
            ]);
        }

        return redirect()->to('operator')->with('success', "Data berhasil diubah!");
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

        $data = User::find($id);
        $data->delete();

        return redirect()->to('operator')->with('success', "Data berhasil dihapus!");

    }
}
