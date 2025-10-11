<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';

    protected $fillable = [
        'nama_layanan',
        'kode_layanan',
        'deskripsi',
    ];

    /**
     * Relasi ke model Antrean
     * Satu layanan bisa memiliki banyak antrean
     */
    public function antrean()
    {
        return $this->hasMany(Antrean::class);
    }

    /**
     * Scope untuk mencari layanan berdasarkan kode
     */
    public function scopeKode($query, $kode)
    {
        return $query->where('kode_layanan', $kode);
    }

    /**
     * Scope untuk mencari layanan berdasarkan nama
     */
    public function scopeNama($query, $nama)
    {
        return $query->where('nama_layanan', 'like', "%{$nama}%");
    }
}
