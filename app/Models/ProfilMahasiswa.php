<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'profil_mahasiswa';
    protected $primaryKey = 'mahasiswa_id';
    public static $publicPrefixFileCv = 'public/dokumen/mahasiswa/cv/';

    protected $fillable = [
        'mahasiswa_id',
        'lokasi_id',
        'nama',
        'nim',
        'program_id',
        'angkatan',
        'nomor_telepon',
        'foto_profil',
        'file_cv',
        'file_transkrip_nilai',
        'ipk',
        'verified',
        'completed_profil'
    ];

    protected function fotoProfil(): Attribute
    {
        return Attribute::make(
            get: fn(?string $image) => $image ? url('storage/profile_pictures/' . $image) : null,
        );
    }

    protected function fileCv(): Attribute
    {
        return Attribute::make(
            get: fn(?string $filename) => $filename ? url('storage/dokumen/mahasiswa/cv/' . $filename) : null,
        );
    }

    protected function fileTranskripNilai(): Attribute
    {
        return Attribute::make(
            get: fn(?string $filename) => $filename ? url('storage/dokumen/mahasiswa/transkrip_nilai/' . $filename) : null,
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_id');
    }

    public function preferensiMahasiswa()
    {
        return $this->belongsTo(PreferensiMahasiswa::class, 'mahasiswa_id');
    }

    public function pengalamanMahasiswa()
    {
        return $this->hasMany(PengalamanMahasiswa::class, 'mahasiswa_id');
    }

    public function keahlianMahasiswa()
    {
        return $this->hasMany(KeahlianMahasiswa::class, 'mahasiswa_id');
    }

    public function pengajuanMagang()
    {
        return $this->hasMany(PengajuanMagang::class, 'mahasiswa_id');
    }
}
