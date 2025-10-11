<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'namaLengkap'       => 'required|string|max:255',
            'nik'               => 'required|digits:16',
            'alamat'            => 'required|string|max:255',
            'jenisKelamin'      => 'required|in:L,P',
            'ktp'               => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dokumen_pendukung' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keperluan'         => 'nullable|string|max:255',
        ];

        // Jika form jenis tanah
        if ($this->input('jenis_surat')) {
            $rules['jenis_surat'] = 'required|in:skt,sporadik,waris,hibah,jual_beli,tidak_sengketa,permohonan,lokasi';
        }

        // Jika form jenis keterangan
        if ($this->input('jenisKeterangan')) {
            $rules['jenisKeterangan'] = 'required|in:domisili,usaha,tidak-mampu,kelahiran,kematian';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'namaLengkap.required' => 'Nama lengkap wajib diisi.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus berisi tepat 16 angka.',
            'alamat.required' => 'Alamat wajib diisi.',
            'jenisKelamin.required' => 'Jenis kelamin wajib dipilih.',
            'ktp.required' => 'KTP wajib diunggah.',
            'ktp.mimes' => 'KTP harus berupa file JPG, JPEG, PNG, atau PDF.',
            'jenis_surat.required' => 'Jenis surat tanah wajib dipilih.',
            'jenisKeterangan.required' => 'Jenis surat keterangan wajib dipilih.',
        ];
    }
}
