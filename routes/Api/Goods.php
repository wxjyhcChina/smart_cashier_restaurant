<?php

Route::group([
    'prefix'  => 'goods',
    'as' => 'goods.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'GoodsController@index')->name('index');
        Route::get('/{goods}', 'GoodsController@get')->name('get');
        Route::post('/', 'GoodsController@store')->name('store');
        Route::put('/{goods}', 'GoodsController@update')->name('update');
        Route::delete('/{goods}', 'GoodsController@delete')->name('delete');
    });
});