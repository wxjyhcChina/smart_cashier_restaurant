<?php

Route::group([
    'prefix'  => 'consumeOrders',
    'as' => 'consumeOrders.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'ConsumeOrderController@index')->name('index');
        Route::get('/latest', 'ConsumeOrderController@latestOrder')->name('latestOrder');
        Route::get('/{consumeOrder}', 'ConsumeOrderController@get')->name('get');
        Route::post('/', 'ConsumeOrderController@store')->name('store');
        Route::post('/preCreate', 'ConsumeOrderController@preCreate')->name('preCreate');
        Route::put('/{consumeOrder}/pay', 'ConsumeOrderController@pay')->name('pay');
        Route::put('/{consumeOrder}', 'ConsumeOrderController@update')->name('update');
        Route::put('/{consumeOrder}/close', 'ConsumeOrderController@close')->name('close');
    });
});