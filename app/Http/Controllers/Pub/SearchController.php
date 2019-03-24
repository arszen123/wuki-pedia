<?php
/**
 * Created by PhpStorm.
 * User: after8
 * Date: 3/23/19
 * Time: 6:47 PM
 */

namespace App\Http\Controllers\Pub;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function index(Request $request)
    {
        $tagsString = $request->get('tags');
        $tagsArray = [];
        if (is_string($tagsString)) {
            $tagsArray = explode(',', $tagsString);
        }
        return view('pub.home', [
            'tags' => $tagsString,
            'articlePartial' => 'pub.partial.article_list',
            'topUsers' => UserRepository::getTopEditors(),
            'topArticles' => ArticleRepository::search($tagsArray, User::getSiteLanguage()),
        ]);
    }

}