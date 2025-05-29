<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangIndustri extends Model
{
    use HasFactory;

    protected $table = 'bidang_industri';
    protected $primaryKey = 'bidang_id';
    public $timestamps = false;

    protected $fillable = [
        'nama',
    ];

    public function perusahaan()
    {
        return $this->hasMany(PerusahaanMitra::class, 'bidang_id', 'bidang_id');
    }
}
