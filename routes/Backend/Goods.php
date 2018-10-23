<?php
Route::group([
    'namespace' => 'Goods',
    'middleware' => 'access.routeNeedsPermission:manage-goods',
], function() {
    Route::resource('goods', 'GoodsController', ['except' => ['show', 'edit']]);

    Route::get('goods/get', 'GoodsTableController')->name('goods.get');

    Route::group([
        'prefix' => 'goods/{goods}',
        'middleware' => 'can:view,goods'
    ], function() {

    });
});