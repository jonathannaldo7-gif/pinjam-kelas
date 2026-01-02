<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'code_kelas',
        'kapasitas'
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
