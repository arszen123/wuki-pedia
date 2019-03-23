<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class Article
 * @package App\Models
 *
 * @property-read int $id
 * @property-read int $author_id
 * @property-read \Carbon\Carbon $created_at
 * @property-read \Carbon\Carbon $updated_at
 * @property-read User $author
 * @property-read ArticleDetailsHistory[]|Collection $history
 * @property-read ArticleDetails[]|Collection $details
 * @property-read User[]|Collection $participant
 *
 * @mixin \Eloquent
 */
class Article extends Model
{
    protected $table = 'article';

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(ArticleDetails::class, 'article_id', 'id');
    }

    public function participant()
    {
        return $this->hasManyThrough(User::class, 'article_participant', 'id', 'user_id', 'user_id', 'author_id');
    }

    public function history()
    {
        return $this->hasMany(ArticleDetailsHistory::class, 'article_id', 'id');
    }

    public function isPublished()
    {
        return $this->details->isNotEmpty();
    }
}
