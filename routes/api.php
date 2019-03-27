<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/articles/pending', function () {
    return \App\Models\ArticleDetailsHistory::where('state', \App\Models\ArticleDetailsHistory::STATE_PENDING)->get();
});

Route::get('/articles', function () {
    return \App\Models\ArticleDetailsHistory::whereNotNull('base_id')->get();
});

Route::get('/users', function () {
    $result = [];
    foreach (\App\Models\User::all() as $item) {
        $result[] = $item->email;
    }
    return $result;
});

Route::get('/db/count', function () {
    return \App\Models\User::count()
        + \App\Models\UserLanguage::count()
        + \App\Models\Article::count()
        + \App\Models\ArticleDetailsHistory::count()
        + \App\Models\ArticleDetails::count()
        + \App\Models\ArticleTag::count()
        + \App\Models\ArticleTagHistory::count()
        + \App\Models\ArticleParticipant::count();
});

Route::get('/languages', function () {
    return \Carbon\Language::all();
});

Route::get('/language/types', function () {
    $result = [];
    foreach (\App\Models\UserLanguage::AVAILABLE_TYPES as $type) {
        $result[$type] = [
            'name' => trans("language_type.${type}")
        ];
    }
    return $result;
});