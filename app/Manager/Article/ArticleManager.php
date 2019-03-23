<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/10/19
 * Time: 9:31 AM
 */

namespace App\Manager\Article;


use App\Models\Article;
use App\Models\ArticleDetailsHistory;
use App\Models\Base\BaseModel;
use App\Models\User;
use App\Repository\ArticleRepository;
use PhpMerge\MergeException;
use PhpMerge\PhpMerge;

abstract class ArticleManager
{

    /**
     * @var Article
     */
    private $article;
    /**
     * @var User
     */
    private $user;

    public function __construct($article, $user)
    {
        $this->article = $article;
        $this->user = $user;
    }

    /**
     * @return Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function isMy()
    {
        return $this->getArticle()->author_id == $this->getUser()->id;
    }

    /**
     * @param ArticleDetailsHistory $detailsHistory
     * @param $state
     * @param null $data
     */
    public function setState($detailsHistory, $state, $data = null)
    {
        if (!$this->canEditState()) {
            return;
        }

        $detailsHistory->state = $state;
        $detailsHistory->reviewer_id = $this->getUser()->id;

        if ($this->canApproveState() && $state === ArticleDetailsHistory::STATE_APPROVED) {
            $ad = $detailsHistory->getArticleDetails();
            $ad->save();
            $ad->tag()->delete();
            BaseModel::massInsert($detailsHistory->getArticleTags());
            $detailsHistory->save();

            $this->saveHistoryIfDataChanged($detailsHistory, $data);
            return;
        }

        $detailsHistory->save();
    }

    private function saveHistoryIfDataChanged(
        ArticleDetailsHistory $detailsHistory,
        $data
    ) {
        if ($data) {
            $detailsHistory->fill($data);
            if ($detailsHistory->syncChanges()->getChanges()) {
                $detailsHistory->changes = null;
                $data['base_id'] = $detailsHistory->id;
                ArticleRepository::saveDetails(
                    $this->user,
                    $data,
                    $detailsHistory->article
                );
            }
        }
    }

    public function getMerged(ArticleDetailsHistory $local)
    {
        $merger = new PhpMerge();

        $base = $local->base;
        $remote = $local->article->details()
            ->where('lang_id', $local->lang_id)
            ->first();
        $diff = [];
        foreach ($remote->getMergingKeys() as $item) {
            try {
                $diff[$item] = $merger->merge(
                    $base->$item,
                    $remote->$item,
                    $local->$item
                );
            } catch (MergeException $ex) {
                $diff[$item] = $ex->getMerged();
            }
        }
        $adh = new ArticleDetailsHistory($diff);
        $adh->article_id = $local->article_id;
        $adh->lang_id = $local->lang_id;
        $adh->setTags($local->tag);
        return $adh;
    }

    protected abstract function canApproveState();

    public abstract function canEditState(ArticleDetailsHistory $history = null);

    public abstract function canViewHistory();

}