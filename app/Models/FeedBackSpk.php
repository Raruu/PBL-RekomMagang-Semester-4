<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedBackSpk extends Model
{
    use HasFactory;

    protected $table = 'feedback_spk';
    protected $primaryKey = 'feedback_spk_id';

    protected $fillable = [
        'rating',
        'komentar',
    ];
}
