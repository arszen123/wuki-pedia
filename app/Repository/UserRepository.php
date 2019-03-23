<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/23/19
 * Time: 7:25 PM
 */

namespace App\Repository;

class UserRepository
{

    public static function getTopEditors($limit = 10)
    {
        $sql = '
SELECT U.id, U.name, COUNT(*) AS badge
FROM "USER" U
LEFT JOIN ARTICLE_DETAILS_HISTORY ADH
  ON U.id = ADH.author_id
WHERE ROWNUM <= ?
GROUP BY U.id, U.name
ORDER BY badge DESC
';
        return \DB::select($sql, [$limit]);
    }

}