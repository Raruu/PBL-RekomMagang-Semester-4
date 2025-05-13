<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaan';
    protected $primaryKey = 'perusahaan_id';

    protected $fillable = [
        'lokasi_id',
        'nama_perusahaan',
        'bidang_industri',
        'website',
        'kontak_email',
        'kotak_telepon',
        'is_active',
    ];

    // Relasi ke Lowongan Magang
    public function lowonganMagang()
    {
        return $this->hasMany(LowonganMagang::class, 'perusahaan_id');
    }


    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }
}
