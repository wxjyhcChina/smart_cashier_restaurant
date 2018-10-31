<?php
Route::group([
    'namespace' => 'RechargeOrder',
    'middleware' => 'access.routeNeedsPermission:manage-recharge-order',
], function() {
    Route::resource('rechargeOrder', 'RechargeOrderController', ['except' => ['show', 'update', 'destroy']]);

    Route::get('rechargeOrder/get', 'RechargeOrderTableController')->name('rechargeOrder.get');

    Route::group([
        'prefix' => 'rechargeOrder/{rechargeOrder}',
        'middleware' => 'can:view,rechargeOrder'
    ], function() {
    });
});