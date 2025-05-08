<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeahlianMahasiswa extends Model
{
    use HasFactory;

    const TINGKAT_KEMAMPUAN = [
        'ahli' => 'Ahli',
        'mahir' => 'Mahir',
        'menengah' => 'Menengah',
        'pemula' => 'Pemula',
    ];

    protected $table = 'keahlian_mahasiswa';
    protected $primaryKey = 'id';

    protected $fillable = [
        'keahlian_id',
        'mahasiswa_id',
        'tingkat_kemampuan',
    ];

    public function tingkatKemampuanIndex()
    {
        $enumDefinition = array_reverse(array_keys(self::TINGKAT_KEMAMPUAN));
        return array_search($this->attributes['tingkat_kemampuan'], $enumDefinition);
    }

    public function keahlian()
    {
        return $this->belongsTo(Keahlian::class, 'keahlian_id', 'keahlian_id');
    }
}
