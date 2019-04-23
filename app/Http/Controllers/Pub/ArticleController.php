<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/9/19
 * Time: 4:07 PM
 */

namespace App\Http\Controllers\Pub;


use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use Illuminate\Cache\CacheManager;
use Illuminate\Support\Facades\Input;

class ArticleController extends Controller
{

    public function show($id, CacheManager $cache)
    {
        $langId = Input::get('language', User::getSiteLanguage());
        $cacheKey = "article.${id}.${langId}";
        $data = $cache->get($cacheKey);
        if (true || $data === null) {
            $article = Article::find($id);
            $articleDetails = $article->details->first();
            if ($langId) {
                $articleDetailsWithLang = $article->details()
                    ->where('lang_id', $langId)
                    ->first();
            }
            $articleDetails = $articleDetailsWithLang ?? $articleDetails;
            $data = [
                'article' => $article,
                'articleDetails' => $articleDetails,
                'participants' => $article->getParticipants(),
                'availableLanguages' => $article->details()
                    ->getQuery()
                    ->where('lang_id', '<>', $articleDetails->lang_id)
                    ->pluck('lang_id'),
            ];
            $cache->set($cacheKey, $data, new \DateInterval('PT1H'));
        }

        return view('pub.article.show', $data);
    }

}