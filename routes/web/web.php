<?php

Auth::routes();

Route::get('/', 'Pub\IndexController@index')->name('home');

Route::get('/article/{id}', 'Pub\ArticleController@show')->name('article.show');