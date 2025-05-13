<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPengajuan extends Model
{
    use HasFactory;

    protected $table = 'dokumen_pengajuan';
    protected $primaryKey = 'dokumen_id';

    protected $fillable = [
        'pengajuan_id',
        'jenis_dokumen',
        'path_file',
    ];

    protected function pathFile(): Attribute
    {
        return Attribute::make(
            get: fn(?string $filename) => $filename ? url('storage/dokumen/mahasiswa/' . $filename) : null,
        );
    }

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanMagang::class, 'pengajuan_id');
    }
}
