<?php

Route::group([
    'prefix'  => 'consumeCategories',
    'as' => 'consumeCategories.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'ConsumeCategoryController@index')->name('index');
    });
});