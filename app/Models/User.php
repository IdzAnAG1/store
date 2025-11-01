<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property mixed $password_hash
 * @property int|mixed $role_id
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'user_id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'username',
        'email',
        'phone',
        'address',
        'password_hash',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    use HasApiTokens, Notifiable;
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password_hash'] = Hash::make($value);
    }
    function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
    public function hasRole(string ...$roles)
    {
        $name = optional($this->role)->role_name;
        return $name && in_array(strtolower($name), array_map('strtolower',$roles), true);
    }

    public function isAdmin(): bool   { return $this->hasRole('admin');   }
    public function isManager(): bool { return $this->hasRole('manager'); }
}
