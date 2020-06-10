<?php
Route::group([
    'namespace' => 'Stocks',
    'middleware' => 'access.routeNeedsPermission:manage-stocks',
], function() {
    Route::resource('stocks', 'StocksController', ['except' => ['show']]);

    Route::get('stocks/get', 'StocksTableController')->name('stocks.get');
    Route::get('stocks/dailyConsume', 'StocksController@dailyConsume')->name('stocks.dailyConsume');
    Route::get('stocks/purchase', 'StocksController@purchase')->name('stocks.purchase');
    Route::get('stocks/getDailyConsumeStatistics', 'StocksTableController@getDailyConsumeStatistics')->name('stocks.getDailyConsumeStatistics');
    Route::get('stocks/getPurchase', 'StocksTableController@getPurchase')->name('stocks.getPurchase');

    Route::group([
        'prefix' => 'stocks/{stocks}',
        'middleware' => 'can:view,stocks'
    ], function() {
        Route::get('edit', 'StocksController@edit')->name('stocks.edit');
        Route::get('purchaseInfo', 'StocksController@purchaseInfo')->name('stocks.purchaseInfo');
    });

    Route::group([
        'prefix' => 'stocks/{id}',
    ], function($id) {
        Route::get('purchaseInfo', 'StocksController@purchaseInfo')->name('stocks.purchaseInfo');
        Route::post('keepPurchase', 'StocksController@keepPurchase')->name('stocks.keepPurchase');
    });
});