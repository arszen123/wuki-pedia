<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/23/19
 * Time: 9:36 PM
 */

namespace App\Manager\Article;


use App\Models\ArticleDetailsHistory;

class AdminArticleManager extends ArticleManager
{

    protected function canApproveState()
    {
        return true;
    }

    public function canEditState(ArticleDetailsHistory $history = null)
    {
        return $history === null || (
                    $history !== null
                    && $history->state === ArticleDetailsHistory::STATE_PENDING
                );
    }

    public function canViewHistory()
    {
        return true;
    }
}