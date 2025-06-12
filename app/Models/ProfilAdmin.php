<?php
// app/Models/ProfilAdmin.php
namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Model;

class ProfilAdmin extends Model
{
    protected $table = 'profil_admin';
    protected $primaryKey = 'admin_id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'admin_id',
        'nama',
        'nomor_telepon',
        'foto_profil',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }

    protected function fotoProfil(): Attribute
{
    return Attribute::make(
        get: fn(?string $image) => $image ? asset('storage/profile_pictures/' . $image) : null,
    );
}
}