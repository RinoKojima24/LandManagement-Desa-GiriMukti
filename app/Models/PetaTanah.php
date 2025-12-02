<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetaTanah extends Model
{
    //
    protected $guarded = [];

    public function SuratPermohonan() {
        return $this->belongsTo(SuratPermohonan::class, 'surat_permohonan_id', 'id_permohonan');
    }
}
