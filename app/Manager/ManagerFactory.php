<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/10/19
 * Time: 9:34 AM
 */

namespace App\Manager;

use App\Manager\Article\ArticleManager;
use App\Manager\Exception\NoManagerFoundForModel;
use App\Manager\Exception\NoManagerFoundForUser;
use App\Models\Article as ArticleModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ManagerFactory
{

    /**
     * @param ArticleModel $article
     * @param User $user
     * @return ArticleManager
     * @throws NoManagerFoundForUser
     */
    public static function getArticleManager(ArticleModel $article, User $user)
    {
        if ($user->isUser()) {
            return new Article\UserArticleManager($article, $user);
        }
        if ($user->isRector()) {
            return new Article\RectorArticleManager($article, $user);
        }
        if ($user->isAdmin()) {
            return new Article\AdminArticleManager($article, $user);
        }
        throw new NoManagerFoundForUser();
    }

    /**
     * @param Model $model
     * @param User $user
     * @return Object
     * @throws NoManagerFoundForModel
     * @throws NoManagerFoundForUser
     */
    public static function getManager(Model $model, User $user)
    {
        if ($model instanceof ArticleModel) {
            return self::getArticleManager($model, $user);
        }
        throw new NoManagerFoundForModel();
    }

}