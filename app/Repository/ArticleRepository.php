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
     * @param null|\App\Models\Article $article
     * @throws \Exception|\Throwable
     */
    public static function saveDetails(User $user, array $articleDetails, $article = null)
    {
        $articleDetails['author_id'] = $user->id;
        $articleDetails['state'] = ArticleDetailsHistory::STATE_PENDING;
        $articleDetails['lang_id'] = \Arr::pull($articleDetails, 'language');
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
            $tags = \Arr::pull($articleDetails, 'tag');
            /** @var ArticleDetailsHistory $details */
            $details = $article->history()->make($articleDetails);
            $baseId = self::getLastApprovedStateId($details);
            if ($details->base_id && $baseId !== $details->base_id) {
                $details->base_id = $baseId;
            }
            $details->save();
            $article->saveParticipant($user);
            BaseModel::massInsert($details->createTags($tags));
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

    public static function search(array $tags)
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
ORDER BY tags.idx desc';
        if (!is_array($tags)) {
            $tags = [$tags];
        }
        $tags = implode("','", $tags);
        $sql = sprintf($sql, "'${tags}'");
        return \DB::select($sql);
    }

    public static function suggestPendingArticles(User $user, $limit = 10)
    {
        $sql = "
SELECT adh.article_id, adh.id AS history_id, adh.title, adh.created_at, adh.lang_id
FROM user_language ul
INNER JOIN article_details_history adh ON adh.lang_id = ul.lang_id
WHERE adh.state = :pendingState
AND ROWNUM <= :limit
AND ul.user_id = :userId
ORDER BY DECODE (ul.type,
        'a1', 1, 'a2', 2,
        'b1', 3, 'b2', 4,
        'c1', 5, 'a2', 6, 7
        ) DESC
";
        $result = \DB::select($sql, [
            'pendingState' => ArticleDetailsHistory::STATE_PENDING,
            'limit' => $limit,
            'userId' => $user->id,
        ]);

        if (!empty($result)) {
            return $result;
        }
        $sql = "
SELECT adh.article_id, adh.id AS history_id, adh.title, adh.created_at, adh.lang_id
FROM article_details_history adh 
WHERE adh.state = :pendingState
AND ROWNUM <= :limit
";
        return \DB::select($sql, [
            'pendingState' => ArticleDetailsHistory::STATE_PENDING,
            'limit' => $limit,
        ]);
    }

    public static function searchPendingMRs(User $user, $tags = [], $limit = 10)
    {
        $filter = [
            'userId' => $user->id,
            'limit' => $limit,
            'statePending' => ArticleDetailsHistory::STATE_PENDING,
        ];
        $sql = "
SELECT
  ADH.article_id,
  ADH.id as history_id,
  ADH.created_at,
  ADH.lang_id,
  ADH.title
FROM USER_LANGUAGE UL
INNER JOIN ARTICLE_DETAILS_HISTORY ADH ON (ADH.lang_id = UL.lang_id AND ADH.state = :statePending)
INNER JOIN (
    SELECT article_details_history_id as id, COUNT(*) as idx
    FROM article_tag_history
    WHERE tag IN (%s)
    GROUP BY article_details_history_id
) tags ON ADH.id = tags.id
WHERE UL.user_id = :userId
  AND ROWNUM <= :limit
ORDER BY tags.idx, DECODE (UL.type,
        'a1', 1, 'a2', 2,
        'b1', 3, 'b2', 4,
        'c1', 5, 'a2', 6, 7
        ) DESC";
        $tags = "'" . implode("', '", $tags) . "'";
        $sql = sprintf($sql, $tags);
        return \DB::select($sql, $filter);
    }

}