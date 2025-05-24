<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProfilDosen extends Model
{
    use HasFactory;

    protected $table = 'profil_dosen';
    protected $primaryKey = 'dosen_id';

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

    public function user()
    {
        return $this->belongsTo(User::class, 'dosen_id', 'user_id');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_id');
    }

    protected function fotoProfil(): Attribute
    {
        return Attribute::make(
            get: fn(?string $image) => $image ? url('storage/profile_pictures/' . $image) : null,
        );
    }
}
