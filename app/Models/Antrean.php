<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrean extends Model
{
    use HasFactory;

    protected $table = 'antrean';

    protected $fillable = [
        'user_id',
        'layanan_id',
        'nomor_antrean',
        'tanggal',
        'kode',
        'status', //menunggu, dipanggil,selesai,batal
    ];

    protected $casts = [
        'tanggal' => 'date',

    ];

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Layanan
     */
    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    /**
     * Scope untuk filter status antrean
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan tanggal antrean
     */
    public function scopeTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }
}
