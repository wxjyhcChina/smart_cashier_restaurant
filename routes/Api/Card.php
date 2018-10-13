<?php

Route::group([
    'prefix'  => 'cards',
    'as' => 'cards.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'CardController@index')->name('index');
    });
});