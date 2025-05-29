<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $table = 'program_studi';
    protected $primaryKey = 'program_id';

    protected $fillable = [
        'nama_program',
        'deskripsi',
    ];

    public function profilMahasiswa()
    {
        return $this->hasMany(ProfilMahasiswa::class, 'program_id', 'program_id');
    }

    
    public function profilDosen()
    {
        return $this->hasMany(ProfilDosen::class, 'program_id', 'program_id');
    }
}
