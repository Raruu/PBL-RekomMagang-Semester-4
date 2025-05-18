<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProfilDosen extends Model
{
    use HasFactory;

    // Jika nama tabel bukan 'profil_dosens', nyatakan di sini:
    protected $table = 'profil_dosen';

    // Kolom yang dapat diisi (ubah sesuai kebutuhan)
    protected $fillable = [
        'dosen_id',
        'lokasi_id',
        'nama',
        'nip',
        'program_id',
        'minat_penelitian',
        'nomor_telepon',
        'foto_profil'
    ];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function mahasiswabimbingan()
    {   
        return $this->hasMany(ProfilMahasiswa::class, 'dosen_id');
    }

    /**
     * (Opsional) Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class,'dosen_id', 'user_id');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_id');
    }
    protected function fotoProfil(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('storage/profile_pictures/' . $image),
        );
    }
}
