<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowonganMagang extends Model
{
    use HasFactory;

    const TIPE_KERJA = [
        'remote' => 'Remote',
        'onsite' => 'Onsite',
        'hybrid' => 'Hybrid'
    ];

    protected $table = 'lowongan_magang';
    protected $primaryKey = 'lowongan_id';

    protected $fillable = [
        'perusahaan_id',
        'lokasi_id',
        'judul_lowongan',
        'judul_posisi',
        'deskripsi',
        'gaji',
        'kuota',
        'tipe_kerja_lowongan',
        'tanggal_mulai',
        'tanggal_selesai',
        'batas_pendaftaran',
        'is_active',
    ];

    // Relasi ke perusahaan
    public function perusahaanMitra()
    {
        return $this->belongsTo(PerusahaanMitra::class, 'perusahaan_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagang::class, 'lowongan_id');
    }

    public function persyaratanMagang()
    {
        return $this->hasOne(PersyaratanMagang::class, 'lowongan_id');
    }

    public function keahlianLowongan()
    {
        return $this->hasMany(KeahlianLowongan::class, 'lowongan_id');
    }
}
