<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;
    protected $table = 'log_aktivitas';
    protected $primaryKey = 'log_id';

    protected $fillable = [
        'pengajuan_id',
        'tanggal_log',
        'aktivitas',
        'kendala',
        'solusi',
        'feedback_dosen',
        'jam_kegiatan',
    ];

    public function pengajuanMagang()
    {
        return $this->belongsTo(PengajuanMagang::class, 'pengajuan_id', 'pengajuan_id');
    }
}
