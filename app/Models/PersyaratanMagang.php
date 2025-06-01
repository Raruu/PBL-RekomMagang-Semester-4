<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersyaratanMagang extends Model
{
    use HasFactory;


    protected $table = 'persyaratan_magang';
    protected $primaryKey = 'persyaratan_id';

    protected $fillable = [
        'lowongan_id',
        'minimum_ipk',
        'deskripsi_persyaratan',
        'dokumen_persyaratan',
        'pengalaman',
    ];
}
