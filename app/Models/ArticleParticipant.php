<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/9/19
 * Time: 7:39 PM
 */

namespace App\Models;

class ArticleParticipant
{
    protected $table = 'article_participant';

    protected $primaryKey = null;

    protected $fillable = [
        'article_id', 'user_id'
    ];

    public $timestamps = false;
}