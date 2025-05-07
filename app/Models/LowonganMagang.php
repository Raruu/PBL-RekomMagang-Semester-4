<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LowonganMagang extends Model
{
    use HasFactory;

    protected $table = 'lowongan_magang';
    protected $primaryKey = 'lowongan_id';

    // Jika kamu menggunakan kolom created_at & updated_at
    public $timestamps = true;

    protected $fillable = [
        'perusahaan_id',
        'lokasi_id',
        'judul_posisi',
        'deskripsi',
        'kuota',
        'opsi_remote',
        'tanggal_mulai',
        'tanggal_selesai',
        'batas_pendaftaran',
        'is_active',
    ];

    // Relasi ke perusahaan
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'perusahaan_id');
    }

    // Relasi ke periode
    public function periode()
    {
        return $this->belongsTo(PeriodeMagang::class, 'periode_id');
    }

    // Relasi ke pengajuan magang (jika ingin tahu siapa saja yang melamar)
    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagang::class, 'lowongan_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
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
