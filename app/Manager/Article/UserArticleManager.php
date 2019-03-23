<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/23/19
 * Time: 9:36 PM
 */

namespace App\Manager\Article;

use App\Models\ArticleDetailsHistory;

class UserArticleManager extends RectorArticleManager
{

    protected function canApproveState()
    {
        return parent::canApproveState() && $this->getArticle()->details->isNotEmpty();
    }

    public function canEditState(ArticleDetailsHistory $history = null)
    {
        return parent::canEditState($history) && $this->isMy() && $this->canApproveState();
    }

    public function canViewHistory()
    {
        return $this->isMy();
    }
}