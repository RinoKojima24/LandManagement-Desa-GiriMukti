<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPermohonan extends Model
{
    // surat_keterangan
    protected $table = 'surat_permohonan';
    protected $guarded = [];

    public function JenisSurat() {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat', 'id_jenis_surat');
    }

}
