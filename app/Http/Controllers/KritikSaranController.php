<?php

namespace App\Http\Controllers;

use App\Models\Kritik_saran;
use App\Models\User;
use App\Http\Requests\StoreKritik_saranRequest;
use App\Http\Requests\UpdateKritik_saranRequest;
use App\Helpers\NotificationHelper;
use Database\Seeders\NontificationFactory;

class KritikSaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('web.Kritik-saran');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKritik_saranRequest $request)
    {
        $data = $request->validated();
         Kritik_Saran::create([
        'user_id'    => auth()->id(),
        'nama'       => auth()->user()->nama_petugas,
        'jenis'      => $data['jenis'],
        'pesan'      => $data['pesan'],
        'status'     => 'baru',
        'created_at' => now(),
    ]);
    NotificationHelper::toAdmin(Auth()->id(),$data['jenis'],$data['pesan']);

    return redirect()->route('home')->with('success', 'Kritik atau saran berhasil dikirim!');
    }

    /**
     * Display the specified resource.
     */
    // public function show(Kritik_saran $kritik_saran)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Kritik_saran $kritik_saran)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(UpdateKritik_saranRequest $request, Kritik_saran $kritik_saran)
    // {
    //     $kritik_saran->update($request->validated());
    //     return redirect()->back()->with('success', 'Kritik atau saran berhasil dikirim!');
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kritik_saran $kritik_saran)
    {
        //
    }
}
