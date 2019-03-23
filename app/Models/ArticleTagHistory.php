<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/22/19
 * Time: 6:44 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleTagHistory
 * @package App\Models
 * @mixin \Eloquent
 */
class ArticleTagHistory extends Model
{
    protected $table = 'article_tag_history';

    protected $primaryKey = 'article_details_history_id';

    protected $fillable = [
        'article_details_history_id', 'tag',
    ];
    public $timestamps = false;
}