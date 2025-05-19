<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';
    protected $primaryKey = 'lokasi_id';

    protected $fillable = [
        'alamat',
        'latitude',
        'longitude',
    ];
    public function profilDosen()
{
    return $this->belongsTo(ProfilDosen::class, 'dosen_id', 'dosen_id');
}

}
