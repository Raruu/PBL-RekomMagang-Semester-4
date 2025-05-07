<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeahlianLowongan extends Model
{
    use HasFactory;

    protected $table = 'keahlian_lowongan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'lowongan_id',
        'keahlian_id',
        'kemampuan_minimum',
    ];

    public function kemampuanMinimumIndex()
    {
        $enumDefinition = [
            'pemula',
            'menengah',
            'mahir',
            'ahli',
        ];
        return array_search($this->attributes['kemampuan_minimum'], $enumDefinition);
    }
}
