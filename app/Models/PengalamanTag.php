<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengalamanTag extends Model
{
    use HasFactory;

    protected $table = 'pengalaman_tag';
    protected $primaryKey = 'pengalaman_id';

    protected $fillable = [
        'keahlian_id',
    ];

    public function keahlian()
    {
        return $this->belongsTo(Keahlian::class, 'keahlian_id');
    }
}
