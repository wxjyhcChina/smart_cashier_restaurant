<?php

Route::group([
    'prefix'  => 'shops',
    'as' => 'shops.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'ShopController@index')->name('index');
    });
});g