<?php
Route::group([
    'namespace' => 'RechargeOrder',
    'middleware' => 'access.routeNeedsPermission:manage-recharge-order',
], function() {
    Route::resource('rechargeOrder', 'RechargeOrderController', ['except' => ['show', 'update', 'destroy']]);

    Route::get('rechargeOrder/get', 'RechargeOrderTableController')->name('rechargeOrder.get');
    Route::get('rechargeOrder/searchOrder', 'RechargeOrderController@searchOrder')->name('rechargeOrder.searchOrder');
    Route::post('rechargeOrder/export', 'RechargeOrderController@export')->name('rechargeOrder.export');

    Route::group([
        'prefix' => 'rechargeOrder/{rechargeOrder}',
        'middleware' => 'can:view,rechargeOrder'
    ], function() {
    });
});