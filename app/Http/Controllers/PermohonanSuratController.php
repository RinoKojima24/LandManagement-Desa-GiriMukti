<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PermohonanSuratController extends Controller
{
    /**
     * Display a listing of all permohonan (gabungan surat_keterangan dan surat_permohonan)
     */
    public function index()
    {
        // Ambil data dari surat_keterangan
        $suratKeterangan = DB::table('surat_keterangan as sk')
            ->join('jenis_surat as js', 'sk.id_jenis_surat', '=', 'js.id_jenis_surat')
            ->leftJoin('users as u', 'sk.created_by', '=', 'u.id')
            ->select(
                'sk.id_permohonan',
                'sk.nik',
                'sk.nama_lengkap',
                'sk.jenis_kelamin',
                'sk.alamat',
                'sk.keperluan',
                'sk.status',
                'sk.created_at',
                'js.name as nama_jenis_surat',
                'u.nama_petugas as created_by_name',
                DB::raw("'surat_keterangan' as tipe_surat")
            )
            ->get();

        // Ambil data dari surat_permohonan
        $suratPermohonan = DB::table('surat_permohonan as sp')
            ->join('jenis_surat as js', 'sp.id_jenis_surat', '=', 'js.id_jenis_surat')
            ->leftJoin('users as u', 'sp.created_by', '=', 'u.id')
            ->select(
                'sp.id_permohonan',
                'sp.nik',
                'sp.nama_lengkap',
                'sp.jenis_kelamin',
                'sp.alamat',
                DB::raw("NULL as keperluan"),
                'sp.status',
                'sp.created_at',
                'js.name as nama_jenis_surat',
                'u.nama_petugas as created_by_name',
                DB::raw("'surat_permohonan' as tipe_surat")
            )
            ->get();

        // Gabungkan kedua collection
        $permohonan = $suratKeterangan->merge($suratPermohonan)->sortByDesc('created_at');

        return view('admin.dashboard-permohonan.index', compact('permohonan'));
    }

    /**
     * Show the form for creating a new permohonan
     */
    public function create()
    {
        $jenisSurat = DB::table('jenis_surat')->get();
        return view('admin.dashboard-permohonan.create', compact('jenisSurat'));
    }

    /**
     * Store a newly created permohonan in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'tipe_surat' => 'required|in:surat_keterangan,surat_permohonan',
            'nik' => 'required|string|size:16',
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string|max:244',
            'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis_surat',
            'keperluan' => 'required_if:tipe_surat,surat_keterangan|nullable|string|max:255',
            'ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_pendukung' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'tipe_surat.required' => 'Tipe surat harus dipilih',
            'nik.required' => 'NIK harus diisi',
            'nik.size' => 'NIK harus 16 digit',
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'alamat.required' => 'Alamat harus diisi',
            'id_jenis_surat.required' => 'Jenis surat harus dipilih',
            'keperluan.required_if' => 'Keperluan harus diisi untuk surat keterangan',
        ]);

        $data = [
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'id_jenis_surat' => $request->id_jenis_surat,
            'status' => 'pending',
            'created_by' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Upload KTP jika ada
        if ($request->hasFile('ktp')) {
            $ktpPath = $request->file('ktp')->store('ktp', 'public');
            $data['ktp'] = $ktpPath;
        }

        // Upload Dokumen Pendukung jika ada
        if ($request->hasFile('dokumen_pendukung')) {
            $dokumenPath = $request->file('dokumen_pendukung')->store('dokumen_pendukung', 'public');
            $data['dokumen_pendukung'] = $dokumenPath;
        }

        // Insert berdasarkan tipe surat
        if ($request->tipe_surat == 'surat_keterangan') {
            $data['keperluan'] = $request->keperluan;
            DB::table('surat_keterangan')->insert($data);
        } else {
            DB::table('surat_permohonan')->insert($data);
        }

        return redirect()->route('dashboard-permohonan.index')
            ->with('success', 'Permohonan berhasil diajukan');
    }

    /**
     * Display the specified permohonan
     */
    public function show($tipe, $id)
    {
        if ($tipe == 'surat_keterangan') {
            $permohonan = DB::table('surat_keterangan as sk')
                ->join('jenis_surat as js', 'sk.id_jenis_surat', '=', 'js.id_jenis_surat')
                ->leftJoin('users as u', 'sk.created_by', '=', 'u.id')
                ->where('sk.id_permohonan', $id)
                ->select('sk.*', 'js.name as nama_jenis_surat', 'u.nama_petugas as created_by_name')
                ->first();
        } else {
            $permohonan = DB::table('surat_permohonan as sp')
                ->join('jenis_surat as js', 'sp.id_jenis_surat', '=', 'js.id_jenis_surat')
                ->leftJoin('users as u', 'sp.created_by', '=', 'u.id')
                ->where('sp.id_permohonan', $id)
                ->select('sp.*', 'js.name as nama_jenis_surat', 'u.nama_petugas  as created_by_name')
                ->first();
        }

        if (!$permohonan) {
            return redirect()->route('dashboard-permohonan.index')
                ->with('error', 'Data tidak ditemukan');
        }

        return view('admin.dashboard-permohonan.show', compact('permohonan', 'tipe'));
    }

    /**
     * Show the form for editing the specified permohonan
     */
    public function edit($tipe, $id)
    {
        $jenisSurat = DB::table('jenis_surat')->get();

        if ($tipe == 'surat_keterangan') {
            $permohonan = DB::table('surat_keterangan')->where('id_permohonan', $id)->first();
        } else {
            $permohonan = DB::table('surat_permohonan')->where('id_permohonan', $id)->first();
        }

        if (!$permohonan) {
            return redirect()->route('dashboard-permohonan.index')
                ->with('error', 'Data tidak ditemukan');
        }

        return view('admin.dashboard-permohonan.edit', compact('permohonan', 'tipe', 'jenisSurat'));
    }

    /**
     * Update the specified permohonan in storage
     */
    public function update(Request $request, $tipe, $id)
    {
        $request->validate([
            'nik' => 'required|string|size:16',
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string|max:244',
            'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis_surat',
            'keperluan' => 'required_if:tipe,' . $tipe . ',surat_keterangan|nullable|string|max:255',
            'status' => 'nullable|in:pending,verifikasi,reject',
            'ktp' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_pendukung' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $data = [
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'id_jenis_surat' => $request->id_jenis_surat,
            'updated_at' => now(),
        ];

        if ($request->has('status')) {
            $data['status'] = $request->status;
        }

        // Ambil data lama untuk hapus file jika ada upload baru
        $table = $tipe == 'surat_keterangan' ? 'surat_keterangan' : 'surat_permohonan';
        $oldData = DB::table($table)->where('id_permohonan', $id)->first();

        // Upload KTP baru jika ada
        if ($request->hasFile('ktp')) {
            // Hapus file lama
            if ($oldData && $oldData->ktp) {
                Storage::disk('public')->delete($oldData->ktp);
            }
            $ktpPath = $request->file('ktp')->store('ktp', 'public');
            $data['ktp'] = $ktpPath;
        }

        // Upload Dokumen Pendukung baru jika ada
        if ($request->hasFile('dokumen_pendukung')) {
            // Hapus file lama
            if ($oldData && $oldData->dokumen_pendukung) {
                Storage::disk('public')->delete($oldData->dokumen_pendukung);
            }
            $dokumenPath = $request->file('dokumen_pendukung')->store('dokumen_pendukung', 'public');
            $data['dokumen_pendukung'] = $dokumenPath;
        }

        // Update berdasarkan tipe surat
        if ($tipe == 'surat_keterangan') {
            $data['keperluan'] = $request->keperluan;
            DB::table('surat_keterangan')->where('id_permohonan', $id)->update($data);
        } else {
            DB::table('surat_permohonan')->where('id_permohonan', $id)->update($data);
        }

        return redirect()->route('dashboard-permohonan.index')
            ->with('success', 'Permohonan berhasil diperbarui');
    }

    /**
     * Remove the specified permohonan from storage
     */
    public function destroy($tipe, $id)
    {
        $table = $tipe == 'surat_keterangan' ? 'surat_keterangan' : 'surat_permohonan';
        $permohonan = DB::table($table)->where('id_permohonan', $id)->first();

        if (!$permohonan) {
            return redirect()->route('dashboard-permohonan.index')
                ->with('error', 'Data tidak ditemukan');
        }

        // Hapus file jika ada
        if ($permohonan->ktp) {
            Storage::disk('public')->delete($permohonan->ktp);
        }
        if ($permohonan->dokumen_pendukung) {
            Storage::disk('public')->delete($permohonan->dokumen_pendukung);
        }

        // Hapus data
        DB::table($table)->where('id_permohonan', $id)->delete();

        return redirect()->route('dashboard-permohonan.index')
            ->with('success', 'Permohonan berhasil dihapus');
    }

    /**
     * Update status permohonan
     */
    public function updateStatus(Request $request, $tipe, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,verifikasi,reject'
        ]);

        $table = $tipe == 'surat_keterangan' ? 'surat_keterangan' : 'surat_permohonan';

        DB::table($table)
            ->where('id_permohonan', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Status berhasil diperbarui');
    }
}
