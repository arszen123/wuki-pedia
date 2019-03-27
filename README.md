# wuki-pedia

### Non trivial ORACLE statements
#### Select newly created articles
```oracle
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
ORDER BY A.created_at DESC
``` 
Deprecated because the article details doesn't created until the first history doesn't get approved.
#### Search articles
```oracle
SELECT a.id, a.updated_at, ad.title
FROM article a
LEFT JOIN article_details ad ON ad.article_id = a.id
INNER JOIN (
    SELECT article_id, COUNT(*)/COUNT(lang_id) as idx
    FROM article_tag
    WHERE tag IN (:tags)
    GROUP BY article_id
) tags ON a.id = tags.article_id
WHERE ad.lang_id = :langId
ORDER BY tags.idx desc
```
#### Search pending modification requests
```oracle
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
    WHERE tag IN (:tags)
    GROUP BY article_details_history_id
) tags ON ADH.id = tags.id
WHERE UL.user_id = :userId
  AND ROWNUM <= :limit
ORDER BY tags.idx, DECODE (UL.type,
        'a1', 1, 'a2', 2,
        'b1', 3, 'b2', 4,
        'c1', 5, 'a2', 6, 7
        ) DESC
```
---
### Trivial ORACLE statements (Maybe these will be accepted too)
#### Get top authors
```oracle
SELECT U.id, U.name, COUNT(*) AS badge
FROM "USER" U
LEFT JOIN ARTICLE_DETAILS_HISTORY ADH
  ON U.id = ADH.author_id
WHERE ROWNUM <= ?
GROUP BY U.id, U.name
ORDER BY badge DESC
```
### Get user pending articles
```oracle
SELECT  A.id, ADH.id AS history_id, ADH.title, A.created_at, ADH.lang_id
FROM ARTICLE A
LEFT JOIN ARTICLE_DETAILS AD ON A.id = AD.article_id
LEFT JOIN ARTICLE_DETAILS_HISTORY ADH ON A.id = ADH.article_id
WHERE A.author_id = :userId
  AND ADH.state = :pendingState
  AND AD.article_id IS NULL
```
### Select newly created articles with language
```oracle
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
ORDER BY A.created_at DESC
```
### Suggest modification request by the user language knowledge
```oracle
SELECT
       adh.article_id,
       adh.id AS history_id,
       adh.title,
       adh.created_at,
       adh.lang_id
FROM user_language ul
INNER JOIN article_details_history adh ON adh.lang_id = ul.lang_id
WHERE adh.state = :pendingState
AND ROWNUM <= :limit
ORDER BY DECODE (ul.type,
        'a1', 1, 'a2', 2,
        'b1', 3, 'b2', 4,
        'c1', 5, 'a2', 6, 7
        ) DESC
```
```oracle
SELECT
       adh.article_id,
       adh.id AS history_id,
       adh.title,
       adh.created_at,
       adh.lang_id,
       CASE
         WHEN ul.type = 'a1' THEN 1
         WHEN ul.type = 'a2' THEN 2
         WHEN ul.type = 'b1' THEN 3
         WHEN ul.type = 'b2' THEN 4
         WHEN ul.type = 'c1' THEN 5
         WHEN ul.type = 'c2' THEN 6
         ELSE 7
       END AS order_number
FROM user_language ul
INNER JOIN article_details_history adh ON adh.lang_id = ul.lang_id
WHERE adh.state = :pendingState
AND ROWNUM <= :limit
ORDER BY order_number DESC
```