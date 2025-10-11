<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kritik_saran extends Model
{
    protected $table = 'kritik_saran';
    protected $fillable = [
        'user_id', 'nama', 'jenis', 'pesan', 'status', 'tanggapan'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
