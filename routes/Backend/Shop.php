<?php
Route::group([
    'namespace' => 'shop',
    'middleware' => 'access.routeNeedsPermission:manage-shop',
], function() {
    Route::resource('shop', 'ShopController', ['except' => ['show']]);

    Route::get('shop/get', 'ShopTableController')->name('shop.get');

    Route::group([
        'prefix' => 'shop/{shop}',
        'middleware' => 'can:view,shop'
    ], function() {
        Route::get('edit', 'ShopController@edit')->name('shop.edit');
        Route::get('mark/{status}', 'ShopController@mark')->name('shop.mark')->where(['status' => '[0,1]']);
    });
});