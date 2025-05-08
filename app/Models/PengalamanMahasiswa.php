<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengalamanMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'pengalaman_mahasiswa';
    protected $primaryKey = 'pengalaman_id';

    protected $fillable = [
        'mahasiswa_id',
        'nama_pengalaman',
        'tipe_pengalaman',
        'path_file',
        'deskripsi_pengalaman',
        'periode_mulai',
        'periode_selesai',
    ];

    public function pengalamanTag()
    {
        return $this->hasMany(PengalamanTag::class, 'pengalaman_id');
    }

    public function pengalamanTagBelongsToMany()
    {
        return $this->belongsToMany(Keahlian::class, 'pengalaman_tag', 'pengalaman_id', 'keahlian_id')
            ->withTimestamps();
    }
}
