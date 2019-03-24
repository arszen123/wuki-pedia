<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/24/19
 * Time: 8:02 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Class UserLanguage
 * @package App\Models
 *
 * @property-read  integer $id
 * @property string $lang_id
 * @property string $type
 * @property-read User $user
 */
class UserLanguage extends Model
{
    const TYPE_A1 = 'a1';
    const TYPE_A2 = 'a2';
    const TYPE_B1 = 'b1';
    const TYPE_B2 = 'b2';
    const TYPE_C1 = 'c1';
    const TYPE_C2 = 'c2';

    const AVAILABLE_TYPES = [
        self::TYPE_A1,
        self::TYPE_A2,
        self::TYPE_B1,
        self::TYPE_B2,
        self::TYPE_C1,
        self::TYPE_C2,
    ];

    protected $table = 'user_language';

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'lang_id', 'type'
    ];

    public $timestamps = false;

    public function user()
    {
        $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getPublicName()
    {
        return \HTML::languageByCode($this->lang_id);
    }

    public function getPublicType()
    {
        return __($this->type);
    }

}