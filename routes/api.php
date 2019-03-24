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