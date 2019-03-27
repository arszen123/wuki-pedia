<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/9/19
 * Time: 7:39 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleParticipant
 * @package App\Models
 *
 * @property integer $article_id
 * @property integer $user_id
 */
class ArticleParticipant extends Model
{
    protected $table = 'article_participant';

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'article_id', 'user_id'
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public $timestamps = false;
}