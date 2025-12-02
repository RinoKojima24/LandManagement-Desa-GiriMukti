<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeterangan extends Model
{
    // id_jenis_surat
    protected $table = 'surat_keterangan';
    protected $guarded = [];

    public function JenisSurat() {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat', 'id_jenis_surat');
    }
}
