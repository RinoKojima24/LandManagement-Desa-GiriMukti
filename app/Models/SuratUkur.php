<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratUkur extends Model
{
    //
    protected $guarded = [];

    public function DikeluarkanSuratUkur() {
        return $this->hasMany(DikeluarkanSuratUkur::class);
    }
}
