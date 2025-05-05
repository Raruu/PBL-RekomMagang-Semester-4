<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;

    protected $table = 'perusahaan';
    protected $primaryKey = 'perusahaan_id';
    public $timestamps = false; // ubah ke true jika kamu menambahkan created_at dan updated_at

    protected $fillable = [
        'nama_perusahaan',
        'bidang_industri',
        'alamat',
        'kota',
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
}
