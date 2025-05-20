<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'feedback_mahasiswa';
    protected $primaryKey = 'feedback_id';

    protected $fillable = [
        'pengajuan_id',
        'rating',
        'komentar',
        'pengalaman_belajar',
        'kendala',
        'saran',
    ];

    
    public function pengajuanMagang()
    {
        return $this->belongsTo(PengajuanMagang::class, 'pengajuan_id');
    }
}
