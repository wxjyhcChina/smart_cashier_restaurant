<?php
Route::group([
    'namespace' => 'Statistics',
    'prefix' => 'statistics',
    'middleware' => 'access.routeNeedsPermission:manage-statistics',
], function() {

    Route::get('departmentStatistics', 'StatisticsController@departmentStatistics')->name('statistics.departmentStatistics');
    Route::get('getDepartmentStatistics', 'StatisticsController@getDepartmentStatistics')->name('statistics.getDepartmentStatistics');
    Route::get('getDepartmentStatisticsOrder', 'StatisticsController@getDepartmentStatisticsOrder')->name('statistics.getDepartmentStatisticsOrder');
    Route::get('departmentStatisticsExport', 'StatisticsController@departmentStatisticsExport')->name('statistics.departmentStatisticsExport');

    Route::get('consumeCategoryStatistics', 'StatisticsController@consumeCategoryStatistics')->name('statistics.consumeCategoryStatistics');
    Route::get('getConsumeCategoryStatistics', 'StatisticsController@getConsumeCategoryStatistics')->name('statistics.getConsumeCategoryStatistics');
    Route::get('getConsumeCategoryStatisticsOrder', 'StatisticsController@getConsumeCategoryStatisticsOrder')->name('statistics.getConsumeCategoryStatisticsOrder');
    Route::get('consumeCategoryStatisticsExport', 'StatisticsController@consumeCategoryStatisticsExport')->name('statistics.consumeCategoryStatisticsExport');

    Route::get('dinningTimeStatistics', 'StatisticsController@dinningTimeStatistics')->name('statistics.dinningTimeStatistics');
    Route::get('getDinningTimeStatistics', 'StatisticsController@getDinningTimeStatistics')->name('statistics.getDinningTimeStatistics');
    Route::get('getDinningTimeStatisticsOrder', 'StatisticsController@getDinningTimeStatisticsOrder')->name('statistics.getDinningTimeStatisticsOrder');
    Route::get('dinningTimeStatisticsExport', 'StatisticsController@consumeCategoryExport')->name('statistics.consumeCategoryExport');

    Route::get('shopStatistics', 'StatisticsController@shopStatistics')->name('statistics.shopStatistics');
    Route::get('getShopStatistics', 'StatisticsController@getShopStatistics')->name('statistics.getShopStatistics');
    Route::get('shopStatisticsExport', 'StatisticsController@shopStatisticsExport')->name('statistics.shopStatisticsExport');

    Route::get('foodStatistics', 'StatisticsController@foodStatistics')->name('statistics.foodStatistics');
    Route::get('getFoodStatistics', 'StatisticsController@getFoodStatistics')->name('statistics.getFoodStatistics');
    Route::get('foodStatisticsOrder', 'StatisticsController@foodStatisticsOrder')->name('statistics.foodStatisticsOrder');
});