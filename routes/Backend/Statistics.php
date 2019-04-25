<?php
Route::group([
    'namespace' => 'Statistics',
    'prefix' => 'statistics',
    'middleware' => 'access.routeNeedsPermission:manage-statistics',
], function() {

    Route::get('departmentStatistics', 'StatisticsController@departmentStatistics')->name('statistics.departmentStatistics');
    Route::get('getDepartmentStatistics', 'StatisticsController@getDepartmentStatistics')->name('statistics.getDepartmentStatistics');
    Route::get('getDepartmentStatisticsOrder', 'StatisticsController@getDepartmentStatisticsOrder')->name('statistics.getDepartmentStatisticsOrder');
    Route::post('departmentStatisticsExport', 'StatisticsController@departmentStatisticsExport')->name('statistics.departmentStatisticsExport');

    Route::get('consumeCategoryStatistics', 'StatisticsController@consumeCategoryStatistics')->name('statistics.consumeCategoryStatistics');
    Route::get('getConsumeCategoryStatistics', 'StatisticsController@getConsumeCategoryStatistics')->name('statistics.getConsumeCategoryStatistics');
    Route::get('getConsumeCategoryStatisticsOrder', 'StatisticsController@getConsumeCategoryStatisticsOrder')->name('statistics.getConsumeCategoryStatisticsOrder');
    Route::post('consumeCategoryStatisticsExport', 'StatisticsController@consumeCategoryStatisticsExport')->name('statistics.consumeCategoryStatisticsExport');

    Route::get('dinningTimeStatistics', 'StatisticsController@dinningTimeStatistics')->name('statistics.dinningTimeStatistics');
    Route::get('getDinningTimeStatistics', 'StatisticsController@getDinningTimeStatistics')->name('statistics.getDinningTimeStatistics');
    Route::get('getDinningTimeStatisticsOrder', 'StatisticsController@getDinningTimeStatisticsOrder')->name('statistics.getDinningTimeStatisticsOrder');
    Route::post('dinningTimeStatisticsExport', 'StatisticsController@dinningTimeStatisticsExport')->name('statistics.dinningTimeStatisticsExport');

    Route::get('shopStatistics', 'StatisticsController@shopStatistics')->name('statistics.shopStatistics');
    Route::get('getShopStatistics', 'StatisticsController@getShopStatistics')->name('statistics.getShopStatistics');
    Route::post('shopStatisticsExport', 'StatisticsController@shopStatisticsExport')->name('statistics.shopStatisticsExport');

    Route::get('goodsStatistics', 'StatisticsController@goodsStatistics')->name('statistics.goodsStatistics');
    Route::get('getGoodsStatistics', 'StatisticsController@getGoodsStatistics')->name('statistics.getGoodsStatistics');
    Route::post('goodsStatisticsExport', 'StatisticsController@goodsStatisticsExport')->name('statistics.goodsStatisticsExport');
});