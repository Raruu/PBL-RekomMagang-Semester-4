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

    protected $fillable = [
        'user_id',
        'lokasi_id',
        'nama', 
        'nim',
        'program_id',
        'semester',
        'nomor_telepon',
        'alamat',
        'foto_profil',
    ];

    protected function fotoProfil(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('storage/profile_pictures/' . $image),
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_id');
    }

    public function preferensiMahasiswa()
    {
        return $this->belongsTo(PreferensiMahasiswa::class, 'mahasiswa_id');
    }
}
