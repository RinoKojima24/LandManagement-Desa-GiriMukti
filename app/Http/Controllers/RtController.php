<?php

namespace App\Http\Controllers;

use App\Models\Rt;
use Illuminate\Http\Request;

class RtController extends Controller
{
    /**
     * Display a listing of the resource. s
     */
    public function index()
    {
        //
        $data['rt'] = Rt::orderBy('created_at', 'ASC');

        if(Request('cari_nama')) {
            $data['rt'] = $data['rt']->where('nama_rt', 'like', '%'.Request('cari_nama').'%');
        }

        $data['rt'] = $data['rt']->get();

        return view('web.rt.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('web.rt.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $rt = new RT;
        $rt->nama = $request->nama;
        $rt->nama_rt = $request->nama_rt;
        $rt->nomor_rt = $request->nomor_rt;
        if ($request->hasFile('geojson')) {
            $gambarFile = $request->file('geojson');
            $gambarPath = $gambarFile->store('rt', 'public');
            $rt->geojson = 'storage/'.$gambarPath;
        }
        $rt->save();

        return redirect()->to('rt')->with('success', "Data berhasil disimpan!");
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
        $data['rt'] = Rt::find($id);

        return view('web.rt.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $rt = RT::find($id);
        $rt->nama = $request->nama;
        $rt->nama_rt = $request->nama_rt;
        $rt->nomor_rt = $request->nomor_rt;
        if ($request->hasFile('geojson')) {
            $gambarFile = $request->file('geojson');
            $gambarPath = $gambarFile->store('rt', 'public');
            $rt->geojson = 'storage/'.$gambarPath;
        }
        $rt->save();

        return redirect()->to('rt')->with('success', 'Data berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $rt = RT::find($id);
        $rt->delete();
        return redirect()->to('rt')->with('success', 'Data berhasil dihapus!');

    }
}
