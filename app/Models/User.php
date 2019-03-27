<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use QCod\ImageUp\HasImageUploads;

/**
 * Class User
 * @package App\Models
 * @property-read int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $academic_degree
 * @property string $institution
 * @property string $specialization
 * @property-read Article[]|Collection $articles
 * @property-read UserLanguage[]|Collection $languages
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasImageUploads;
    public const ROLE_USER = 'user';
    public const ROLE_RECTOR = 'rector';
    public const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role',
        'academic_degree', 'institution', 'specialization',
        'avatar',
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

    protected static $imageFields = [
        'avatar' => [
            'image|max:2000'
        ]
    ];

    protected $table = 'user';

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id', 'id');
    }

    public function languages()
    {
        return $this->hasMany(UserLanguage::class, 'user_id', 'id');
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

    public function createLanguages($languages)
    {
        $result = [];
        foreach ($languages as $lang) {
            $result[] = $this->languages()->make($lang);
        }
        return $result;
    }

    public function getAvatarUrl($absolute = true)
    {
        if (!$this->avatar) {
            return '';
        }
        $port = config('app.port');
        return (($absolute ? (config('app.url') . ($port ? ':' . $port : '') . '/') : '') . $this->avatar);
    }

    public static function getSiteLanguage()
    {
        return $_COOKIE['lang_id'] ?? 'en';
    }
}
