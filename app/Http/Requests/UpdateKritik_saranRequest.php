<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKritik_SaranRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan melakukan request ini.
     */
    public function authorize(): bool
    {
        // Jika hanya admin boleh mengupdate:
        return auth()->check() && auth()->user()->role === 'admin';

        // Kalau semua user boleh update (misal ubah pesannya sendiri):
        // return auth()->check();
    }

    /**
     * Aturan validasi untuk update kritik/saran.
     */
    public function rules(): array
    {
        return [
            'jenis'     => 'nullable|in:kritik,saran',
            'pesan'     => 'nullable|string|min:5',
            'status'    => 'nullable|in:baru,dibaca,ditanggapi',
            'tanggapan' => 'nullable|string|min:3',
        ];
    }

    /**
     * Pesan error custom.
     */
    public function messages(): array
    {
        return [
            'jenis.in' => 'Jenis hanya boleh "kritik" atau "saran".',
            'pesan.min' => 'Pesan minimal 5 karakter.',
            'status.in' => 'Status hanya boleh: baru, dibaca, atau ditanggapi.',
            'tanggapan.min' => 'Tanggapan minimal 3 karakter.',
        ];
    }
}
