<?php

Route::group(['prefix' => '/admin'], function () {
    Route::get('/article', 'Admin\ArticleController@index')->name('admin.article.list');
    Route::get('/article/create', 'Admin\ArticleController@create')->name('article.create');
    Route::post('/article/create', 'Admin\ArticleController@store')->name('article.store');
    Route::get('/article/{id}/edit', 'Admin\ArticleController@edit')->name('article.edit');
    Route::post('/article/{id}/edit', 'Admin\ArticleController@update')->name('article.update');

    Route::get('/article/{id}/state', 'Admin\ArticleController@listModRequests')->name('article.mod.requests');
    Route::get('/article/state/{historyId}/edit', 'Admin\ArticleController@editState')->name('article.state.edit');
    Route::post('/article/state/{historyId}/edit', 'Admin\ArticleController@updateState')->name('article.state.update');

    Route::get('/article/history/{historyId}/view', 'Admin\ArticleController@viewByHistoryId')->name('article.history.view');

    Route::get('/article/{id}/statistic', 'Admin\ArticleController@showStatistic')->name('article.statistic');

});