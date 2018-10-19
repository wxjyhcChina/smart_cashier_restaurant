<?php

Route::group([
    'prefix'  => 'rechargeOrders',
    'as' => 'rechargeOrders.'
], function() {
    Route::post('/recharge_alipay_resp', 'RechargeOrderController@recharge_alipay_resp')->name('recharge_alipay_resp');

    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'RechargeOrderController@index')->name('index');
        Route::post('/', 'RechargeOrderController@store')->name('store');
        Route::put('/{rechargeOrder}/pay', 'RechargeOrderController@pay')->name('pay');
        Route::put('/{rechargeOrder}', 'RechargeOrderController@update')->name('update');
    });
});