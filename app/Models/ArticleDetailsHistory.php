<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/9/19
 * Time: 7:26 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ArticleDetailsHistory
 * @package App\Models
 *
 * @property int id
 * @property int $article_id
 * @property int $author_id
 * @property string $state
 * @property int $reviewer_id
 * @property int $base_id
 * @property string $lang_id
 * @property string $title
 * @property string $context
 * @property-read \Carbon\Carbon $created_at
 * @property-read \Carbon\Carbon $updated_at
 * @property-read Article $article
 * @property-read User $author
 * @property-read User $reviewer
 * @property-read ArticleDetailsHistory $base
 * @property-read \Illuminate\Database\Eloquent\Collection|ArticleTagHistory[] $tag
 *
 * @mixin \Eloquent
 */
class ArticleDetailsHistory extends Model
{
    public const STATE_PENDING = 'pending';
    public const STATE_APPROVED = 'approved';
    public const STATE_EDITED = 'edited';
    public const STATE_REFUSED = 'refused';
    public const AVAILABLE_STATES = [
        self::STATE_APPROVED,
        self::STATE_REFUSED,
    ];

    protected $table = 'article_details_history';

    protected $fillable = [
        'article_id', 'author_id', 'state', 'reviewer_id', 'base_id',
        // extended columns
        'lang_id', 'title', 'context'
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }


    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }


    public function reviewer()
    {
        return $this->belongsTo(User::class, 'id', 'reviewer_id');
    }

    public function base()
    {
        return $this->hasOne(self::class, 'id', 'base_id');
    }

    public function tag()
    {
        return $this->hasMany(ArticleTagHistory::class, 'article_details_history_id', 'id');
    }

    public function setTags($tags)
    {
        $this->tag = $tags;
    }

    /**
     * @return ArticleDetails
     */
    public function getArticleDetails()
    {
        $ad = $this->getArticleDetailsInstance();
        $ad->lang_id = $this->lang_id;
        $ad->title = $this->title;
        $ad->context = $this->context;
        return $ad;
    }

    public function getArticleTags()
    {
        $ad = $this->getArticleDetailsInstance();
        return $ad->createTags(array_flat($this->tag->toArray(), 'tag'));
    }

    private function getArticleDetailsInstance()
    {
        $ad = ArticleDetails::where('lang_id', $this->lang_id)
            ->where('article_id', $this->article_id)
            ->first();
        if (!$ad) {
            $ad = new ArticleDetails();
            $ad->setArticleId($this->article_id);
        }
        return $ad;
    }


    /**
     * @param array|string $tags
     * @return ArticleTag[]
     */
    public function createTags(array $tags)
    {
        $newTags = [];
        foreach ($tags as $tag) {
            $newTags[] = new ArticleTagHistory([
                'article_details_history_id' => $this->id,
                'tag'        => $tag,
            ]);
        }
        return $newTags;
    }

}