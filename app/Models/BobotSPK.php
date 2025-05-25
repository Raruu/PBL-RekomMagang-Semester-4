<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BobotSPK extends Model
{
    use HasFactory;

    protected $table = 'bobot_spk';
    protected $primaryKey = 'bobot_spk_id';

    protected $fillable = [
        'bobot',
        'jenis_bobot',
        'bobot_prev',
    ];
}
