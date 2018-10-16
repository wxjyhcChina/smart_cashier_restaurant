<?php

Route::group([
    'prefix'  => 'payMethods',
    'as' => 'payMethods.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'PayMethodController@index')->name('index');
    });
});