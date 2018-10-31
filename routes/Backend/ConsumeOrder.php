<?php
Route::group([
    'namespace' => 'ConsumeOrder',
    'middleware' => 'access.routeNeedsPermission:manage-consume-order',
], function() {
    Route::resource('consumeOrder', 'ConsumeOrderController', ['except' => ['show', 'update', 'destroy']]);

    Route::get('consumeOrder/get', 'ConsumeOrderTableController')->name('consumeOrder.get');

    Route::group([
        'prefix' => 'consumeOrder/{consumeOrder}',
        'middleware' => 'can:view,consumeOrder'
    ], function() {
        Route::get('info', 'ConsumeOrderController@show')->name('consumeOrder.info');
    });
});