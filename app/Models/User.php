<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'email',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Get role
     */
    public function getRole()
    {
        return $this->role;
    }

    public function getPhotoProfile()
    {
        if ($this->role == 'admin') {
            return null;
        }
        if ($this->role == 'dosen') {
            return null;
        }
        if ($this->role == 'mahasiswa') {
            $path = ProfilMahasiswa::where('mahasiswa_id', $this->user_id)->first();
            if ($path == null) {
                return null;
            }
            $path = $path->foto_profil;
            return $path == url('storage/profile_pictures/') ? null : $path;
        }
        return null;
    }

    public function profilAdmin()
    {
        return $this->hasOne(ProfilAdmin::class, 'admin_id', 'user_id');
    }

    public function profilDosen()
    {
        return $this->hasOne(ProfilDosen::class, 'dosen_id', 'user_id');
    }


    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }
    public function profilMahasiswa()
    {
        return $this->hasOne(ProfilMahasiswa::class, 'mahasiswa_id', 'user_id');
    }
}
