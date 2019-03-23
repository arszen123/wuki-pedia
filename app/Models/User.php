<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App\Models
 * @property-read int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property-read Article[]|Collection $articles
 */
class User extends Authenticatable
{
    use Notifiable;
    public const ROLE_USER = 'user';
    public const ROLE_RECTOR = 'rector';
    public const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table = 'user';

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id', 'id');
    }

    /**
     * @return bool
     */
    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * @return bool
     */
    public function isRector()
    {
        return $this->role === self::ROLE_RECTOR;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
}
