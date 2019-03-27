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
        return $this->hasManyThrough(User::class, ArticleParticipant::class, 'article_id', 'id', 'article_id', 'user_id');
    }

    public function getParticipants()
    {
        /** @var ArticleParticipant[] $participants */
        $participants = ArticleParticipant::where('article_id', $this->id)->get();
        $users = [];
        foreach ($participants as $participant) {
            $users[] = $participant->user;
        }
        return $users;
    }

    public function history()
    {
        return $this->hasMany(ArticleDetailsHistory::class, 'article_id', 'id');
    }

    public function isPublished()
    {
        return $this->details->isNotEmpty();
    }

    public function saveParticipant(User $user)
    {
        $exists = false;
        foreach ($this->participant as $participant) {
            if ($participant->id === $user->id) {
                $exists = true;
                break;
            }

        }
        if (!$exists) {
            $part = new ArticleParticipant();
            $part->article_id = $this->id;
            $part->user_id = $user->id;
            $part->save();
        }
    }
}
