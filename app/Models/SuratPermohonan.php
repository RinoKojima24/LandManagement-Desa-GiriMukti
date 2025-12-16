<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPermohonan extends Model
{
    // surat_keterangan
    protected $table = 'surat_permohonans';
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function JenisSurat() {
        return $this->belongsTo(JenisSurat::class, 'id_jenis_surat', 'id_jenis_surat');
    }

    public function Pernyataan1() {
        return $this->hasOne(PernyataanPemasanganTenda::class);
    }


    public function BeritaAcara() {
        return $this->hasOne(BeritaAcara::class);
    }

    public function Pernyataan2() {
        return $this->hasOne(PernyataanPenguasaan::class);
    }

}
