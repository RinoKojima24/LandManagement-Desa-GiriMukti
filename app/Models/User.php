<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasFactory;

    // Nama tabel
    protected $table = 'users';

    // Primary key
    protected $primaryKey = 'id';

    // Kalau primary key bukan auto-increment integer, atur $incrementing dan $keyType
    public $incrementing = true;
    protected $keyType = 'int';

    // Field yang bisa diisi mass assignment
    protected $fillable = [
        'username',
        'password',
        'nama_petugas',
        'no_telepon',
        'role',
        'last_login',
        'avatar',

    ];

    // Field yang disembunyikan (misalnya saat convert ke JSON)
    protected $hidden = [
        'password',
    ];

    // Casting otomatis tipe data
    protected $casts = [
        'last_login'  => 'datetime',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];
}
