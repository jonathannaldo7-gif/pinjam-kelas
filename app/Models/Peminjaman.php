<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'nama_matkul',
        'kelas_matkul',
        'kode_matkul',
        'keterangan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
