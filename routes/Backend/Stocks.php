<?php
Route::group([
    'namespace' => 'Stocks',
    'middleware' => 'access.routeNeedsPermission:manage-stock',
], function() {
    Route::resource('stocks', 'StocksController', ['except' => ['show']]);

    Route::get('stocks/get', 'StocksTableController')->name('stocks.get');
    Route::get('stocks/dailyConsume', 'StocksController@dailyConsume')->name('stocks.dailyConsume');
    Route::get('stocks/getDailyConsumeStatistics', 'StocksTableController@getDailyConsumeStatistics')->name('stocks.getDailyConsumeStatistics');
    Route::group([
        'prefix' => 'stocks/{stocks}',
        'middleware' => 'can:view,stocks'
    ], function() {
        Route::get('edit', 'StocksController@edit')->name('stocks.edit');
    });
});