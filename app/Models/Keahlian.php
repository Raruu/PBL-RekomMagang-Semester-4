<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keahlian extends Model
{
    use HasFactory;

    protected $table = 'keahlian';
    protected $primaryKey = 'keahlian_id';

    protected $fillable = [
        'nama_keahlian',
        'kategori_id',
        'deskripsi',
    ];

    // Relationship dengan Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
