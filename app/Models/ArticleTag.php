<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/22/19
 * Time: 6:43 PM
 */

namespace App\Models;

use App\Models\Traits\MultiplePrimaryKey;
use Illuminate\Database\Eloquent\Model;

class ArticleTag extends Model
{
    use MultiplePrimaryKey;

    protected $table = 'article_tag';

    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = [
        'article_id', 'lang_id', 'tag',
    ];
    public $timestamps = false;

    public function getKeys()
    {
        return ['article_id', 'lang_id'];
    }
}