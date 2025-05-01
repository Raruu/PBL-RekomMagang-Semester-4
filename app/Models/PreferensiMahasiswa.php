<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferensiMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'preferensi_mahasiswa';
    protected $primaryKey = 'mahasiswa_id';

    protected $fillable = [
        'industri_preferensi',
        'lokasi_preferensi',
        'posisi_preferensi',
        'tipe_kerja_preferensi',
    ];
}
