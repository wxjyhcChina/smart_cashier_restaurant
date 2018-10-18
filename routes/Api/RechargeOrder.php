<?php

Route::group([
    'prefix'  => 'rechargeOrders',
    'as' => 'rechargeOrders.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'RechargeOrderController@index')->name('index');
        Route::post('/', 'RechargeOrderController@store')->name('store');
        Route::put('/{rechargeOrder}/pay', 'RechargeOrderController@pay')->name('pay');
        Route::put('/{rechargeOrder}', 'RechargeOrderController@update')->name('update');
    });
});