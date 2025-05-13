<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerusahaanMitra extends Model
{
    use HasFactory;

    protected $table = 'perusahaan';
    protected $primaryKey = 'perusahaan_id'; 
    public $timestamps = true;

    protected $fillable = [
        'lokasi_id',
        'nama_perusahaan',
        'bidang_industri',
        'website',
        'kontak_email',
        'kontak_telepon',
        'is_active',
    ];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }
}

