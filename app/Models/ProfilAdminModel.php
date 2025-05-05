<?php
// app/Models/ProfilAdmin.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilAdminModel extends Model
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

    /**
     * Get the user that owns the admin profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }
}