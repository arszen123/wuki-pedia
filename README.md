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
---
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