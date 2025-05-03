<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeMagang extends Model
{
    use HasFactory;

    protected $table = 'periode_magang';
    protected $primaryKey = 'periode_id';
    public $timestamps = false; // Set true jika ada kolom created_at & updated_at

    protected $fillable = [
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
        'is_active',
    ];

    // Relasi ke Lowongan Magang
    public function lowonganMagang()
    {
        return $this->hasMany(LowonganMagang::class, 'periode_id');
    }
}
