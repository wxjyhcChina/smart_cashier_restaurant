<?php

Route::group([
    'prefix'  => 'goods',
    'as' => 'goods.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'GoodsController@index')->name('index');
        Route::get('/imageUploadToken', 'GoodsController@imageUploadToken')->name('imageUploadToken');
        Route::get('/{goods}', 'GoodsController@get')->name('get');
        Route::post('/', 'GoodsController@store')->name('store');
        Route::get('/{goods}/labelCategories', 'GoodsController@getLabelCategories')->name('getLabelCategories');
        Route::post('/{goods}/labelCategory', 'GoodsController@storeLabelCategory')->name('storeLabelCategory');
        Route::put('/{goods}', 'GoodsController@update')->name('update');
        Route::delete('/{goods}', 'GoodsController@delete')->name('delete');
    });
});