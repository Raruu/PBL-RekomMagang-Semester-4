<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferensiMahasiswa extends Model
{
    use HasFactory;

    const TIPE_KERJA_PREFERENSI = [
        'onsite' => 'Onsite (Work in Office)',
        'remote' => 'Remote (Work from Home)',
        'hybrid' => 'Hybrid (Flexible)',
        'semua' => 'Semua'
    ];

    protected $table = 'preferensi_mahasiswa';
    protected $primaryKey = 'mahasiswa_id';

    protected $fillable = [
        'lokasi_id',
        'mahasiswa_id',
        'industri_preferensi',
        'posisi_preferensi',
        'tipe_kerja_preferensi',
    ];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id', 'lokasi_id');
    }
}
