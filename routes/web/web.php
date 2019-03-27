<?php

Auth::routes(['verify' => true]);

Route::get('/', 'Pub\IndexController@index')->name('home');
Route::get('/languages', 'Pub\IndexController@selectLanguage')->name('language.select');

Route::get('/article/{id}', 'Pub\ArticleController@show')->name('article.show');
Route::get('/search', 'Pub\SearchController@index')->name('article.search');

Route::get('/user/{id}', 'Pub\UserController@view')
    ->where('id', '([0-9]+)|me')
    ->name('user.view');
Route::get('/user/edit', 'Pub\UserController@edit')->name('user.edit');
Route::post('/user/edit', 'Pub\UserController@update')->name('user.update');