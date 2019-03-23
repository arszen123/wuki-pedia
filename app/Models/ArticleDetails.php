<?php

namespace App\Models;

use App\Models\Traits\MultiplePrimaryKey;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleDetails
 * @package App\Models
 *
 * @property-read int $article_id
 * @property string $lang_id
 * @property string $title
 * @property string $context
 * @property-read \Carbon\Carbon $created_at
 * @property-read \Carbon\Carbon $updated_at
 * @property-read Article $article
 */
class ArticleDetails extends Model
{
    use MultiplePrimaryKey;

    protected $table = 'article_details';

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'article_id', 'lang_id', 'title', 'context'
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'id', 'article_id');
    }

    public function tag()
    {
        return $this->hasMany(ArticleTag::class, 'article_id', 'article_id')
            ->where('lang_id', $this->lang_id);
    }

    public function getKeys()
    {
        return [
            'article_id',
            'lang_id',
        ];
    }

    public function setArticleId($id)
    {
        $this->article_id = $id;
    }

    public function getMergingKeys()
    {
        return array_diff($this->getFillable(), ['article_id', 'lang_id']);
    }

    /**
     * @param array|string $tags
     * @return ArticleTag[]
     */
    public function createTags(array $tags)
    {
        $newTags = [];
        foreach ($tags as $tag) {
            $newTags[] = new ArticleTag([
                'article_id' => $this->article_id,
                'lang_id'    => $this->lang_id,
                'tag'        => $tag,
            ]);
        }
        return $newTags;
    }
}
