<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/23/19
 * Time: 6:48 PM
 */

namespace App\Http\Controllers\Pub;


use App\Http\Controllers\Controller;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Carbon\Language;
use Illuminate\Cache\CacheManager;

class IndexController extends Controller
{

    public function index(CacheManager $cache)
    {
        $data = $cache->get('pub.home.data');
        if ($data === null) {
            $data = [
                'tags' => '',
                'articlePartial' => 'pub.partial.article_index',
                'topUsers' => UserRepository::getTopEditors(),
                'topArticles' => ArticleRepository::getTopArticles(),
            ];
            $cache->set('pub.home.data', $data, new \DateInterval('PT1H'));
        }
        return view('pub.home', $data);
    }

    public function selectLanguage()
    {
        return view('pub.languages', [
            'languages' => Language::all(),
        ]);
    }

}