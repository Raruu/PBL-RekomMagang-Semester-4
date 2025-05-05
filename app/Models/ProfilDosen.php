<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilDosen extends Model
{
    use HasFactory;

    // Jika nama tabel bukan 'profil_dosens', nyatakan di sini:
    protected $table = 'profil_dosen';

    // Kolom yang dapat diisi (ubah sesuai kebutuhan)
    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nip',
        'program_id',
        'minat_penelitian',
        'nomor_telepon',
        'foto_profil'
    ];

    /**
     * Relasi: Dosen membimbing banyak mahasiswa
     */
    public function mahasiswaBimbingan()
    {
        return $this->hasMany(ProfilMahasiswa::class, 'dosen_id');
    }

    /**
     * (Opsional) Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
