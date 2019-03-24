<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/10/19
 * Time: 12:49 PM
 */

namespace App\Repository;


use App\Manager\ManagerFactory;
use App\Models\ArticleDetailsHistory;
use App\Models\Base\BaseModel;
use App\Models\User;

class ArticleRepository
{

    /**
     * @param User $user
     * @param array $articleDetails
     * @param null $article
     * @throws \Exception|\Throwable
     */
    public static function saveDetails(User $user, array $articleDetails, $article = null)
    {
        $articleDetails['author_id'] = $user->id;
        $articleDetails['state'] = ArticleDetailsHistory::STATE_PENDING;
        $articleDetails['lang_id'] = \Arr::get($articleDetails, 'language');
        $details = null;

        \DB::transaction(function () use (
            &$article,
            $articleDetails,
            $user,
            &$details
        ) {
            if ($article === null) {
                $article = $user->articles()->make();
                $article->save();
            }
            /** @var ArticleDetailsHistory $details */
            $details = $article->history()->make($articleDetails);
            $baseId = self::getLastApprovedStateId($details);
            if ($details->base_id && $baseId !== $details->base_id) {
                $details->base_id = $baseId;
            }
            $details->save();
            BaseModel::massInsert($details->createTags($articleDetails['tag']));
        });

        $m = ManagerFactory::getArticleManager($article, $user);
        if ($m->canEditState()) {
            $m->setState($details, ArticleDetailsHistory::STATE_APPROVED);
        }
    }

    public static function getLastApprovedStateId($articleDetails)
    {
        $result = ArticleDetailsHistory::where('state', ArticleDetailsHistory::STATE_APPROVED)
            ->where('article_id', $articleDetails->article_id)
            ->where('lang_id', $articleDetails->lang_id)
            ->pluck('id');

        return isset($result[0]) ? $result[0] : null;
    }

    public static function getTopArticles($limit = 10, $filter = [])
    {
        $filter = array_merge($filter, [
            'limit' => $limit,
            'lang_id' => 'hu',
            'stateApproved' => ArticleDetailsHistory::STATE_APPROVED,
        ]);
        $sql = '
SELECT DISTINCT
  A.id,
  U.name AS author,
  A.created_at,
  LANG.lang_id,
  AD.title
FROM ARTICLE A
LEFT JOIN "USER" U ON A.author_id = U.id
LEFT JOIN (
    SELECT DISTINCT 
      article_id,
      first_value(lang_id) over (PARTITION BY article_id ORDER BY updated_at DESC) AS lang_id
    FROM ARTICLE_DETAILS_HISTORY
    WHERE state = :stateApproved
    ) LANG ON A.id = LANG.article_id
INNER JOIN ARTICLE_DETAILS AD ON (LANG.article_id = AD.article_id AND AD.lang_id = LANG.lang_id)
WHERE
  ROWNUM <= :limit
ORDER BY A.created_at DESC';

        if (isset($filter['lang_id'])) {
            $sql = '
SELECT DISTINCT
  A.id,
  U.name AS author,
  A.created_at,
  AD.lang_id,
  AD.title
FROM ARTICLE A
LEFT JOIN "USER" U ON A.author_id = U.id
INNER JOIN ARTICLE_DETAILS AD ON A.id = AD.article_id
WHERE
  ROWNUM <= :limit
  AND AD.lang_id = :lang_id
ORDER BY A.created_at DESC';
            unset($filter['stateApproved']);
        }

        return \DB::select($sql, $filter);
    }

    public static function getUserPendingArticles(User $user)
    {
        $sql = '
SELECT  A.id, ADH.id AS history_id, ADH.title, A.created_at, ADH.lang_id
FROM ARTICLE A
LEFT JOIN ARTICLE_DETAILS AD ON A.id = AD.article_id
LEFT JOIN ARTICLE_DETAILS_HISTORY ADH ON A.id = ADH.article_id
WHERE A.author_id = :userId
AND ADH.state = :pendingState
AND AD.article_id IS NULL';
        return \DB::select($sql, [
            'userId' => $user->id,
            'pendingState' => ArticleDetailsHistory::STATE_PENDING,
        ]);
    }

    public static function search(array $tags, $langId)
    {
        $sql = '
SELECT a.id, a.updated_at, ad.title
FROM article a
LEFT JOIN article_details ad ON ad.article_id = a.id
INNER JOIN (
    SELECT article_id, COUNT(*)/COUNT(lang_id) as idx
    FROM article_tag
    WHERE tag IN (%s)
    GROUP BY article_id
) tags ON a.id = tags.article_id
WHERE ad.lang_id = %s
ORDER BY tags.idx desc';
        $tags = implode("','", $tags);
        $sql = sprintf($sql, "'${tags}'", "'${langId}'");
        return \DB::select($sql);
    }

}