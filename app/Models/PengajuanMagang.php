<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanMagang extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_magang';
    protected $primaryKey = 'pengajuan_id';
    public $timestamps = false;

    protected $fillable = [
        'mahasiswa_id',
        'lowongan_id',
        'dosen_id',
        'tanggal_pengajuan',
        'status',
        'catatan_admin',
        'catatan_mahasiswa',
        'tanggal_mulai',
        'tanggal_selesai',
        'file_sertifikat'
    ];

    // Relasi ke Mahasiswa
    public function profilMahasiswa()
    {
        return $this->belongsTo(ProfilMahasiswa::class, 'mahasiswa_id','mahasiswa_id');
    }

    // Relasi ke Lowongan
    public function lowonganMagang()
    {
        return $this->belongsTo(LowonganMagang::class, 'lowongan_id','lowongan_id');
    }

    // Relasi ke Dosen
    public function profilDosen()
    {
        return $this->belongsTo(ProfilDosen::class, 'dosen_id','dosen_id');
    }

    public function preferensiMahasiswa()
    {
        return $this->belongsTo(PreferensiMahasiswa::class, 'mahasiswa_id','mahasiswa_id');
    }
    
    public function lokasi(){
        return $this->belongsTo(Lokasi::class, 'lokasi_id','lokasi_id');
    }
}
