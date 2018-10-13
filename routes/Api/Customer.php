<?php

Route::group([
    'prefix'  => 'dinningTime',
    'as' => 'dinningTime.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'DinningTimeController@index')->name('index');
        Route::post('/', 'DinningTimeController@create')->name('create');
        Route::put('/{dinningTime}', 'DinningTimeController@update')->name('update');
    });
});