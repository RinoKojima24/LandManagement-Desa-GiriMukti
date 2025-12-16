<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetaTanah extends Model
{
    // s
    protected $guarded = [];

    public function SuratPermohonan() {
        return $this->belongsTo(SuratPermohonan::class, 'surat_permohonan_id', 'id_permohonan');
    }

    public function PendaftaranPertama() {
        return $this->hasOne(PendaftaranPertama::class);
    }

    public function PendaftaranPeralihan() {
        return $this->hasMany(PendaftaranPeralihan::class);
    }

    public function SuratUkur() {
        return $this->hasOne(SuratUkur::class);

    }


}
