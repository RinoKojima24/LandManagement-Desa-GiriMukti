<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKritik_saranRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
     public function rules(): array
    {
        return [
            'nama'      => 'nullable|string|max:100',
            'jenis'     => 'required|in:kritik,saran',
            'pesan'     => 'required|string|min:5',
            'status'    => 'nullable|in:baru,dibaca,ditanggapi',
            'tanggapan' => 'nullable|string',
        ];
    }

    /**
     * Pesan error yang lebih ramah untuk user.
     */
    public function messages(): array
    {
        return [
            'nama.string' => 'Nama harus berupa teks.',
            'jenis.required' => 'Jenis wajib diisi (kritik atau saran).',
            'jenis.in' => 'Jenis hanya boleh "kritik" atau "saran".',
            'pesan.required' => 'Pesan tidak boleh kosong.',
            'pesan.min' => 'Pesan harus memiliki minimal 5 karakter.',
        ];
    }
}
