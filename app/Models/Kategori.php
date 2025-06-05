<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori_keahlian';
    protected $primaryKey = 'kategori_id';
    public $timestamps = true;

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    public function Keahlian()
    {
        return $this->hasMany(Keahlian::class, 'kategori_id');
    }
}
