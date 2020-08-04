<?php

Route::group([
    'prefix'  => 'wx',
], function() {
    Route::any('/test','WxController@test');
    Route::any('/getAllInfo','WxController@getAllInfo');
    Route::any('/getDinnerTime','WxController@getDinnerTime');
    Route::any('/getMenu','WxController@getMenu');
    Route::any('/getWxUserInfo','WxController@getWxUserInfo');
    Route::any('/prePay','WxController@prePay');
    Route::any('/payCallback','WxController@payCallback');
    Route::any('/create', 'WxController@create')->name('create');
    Route::any('/pay', 'WxController@pay')->name('pay');
    Route::any('/payWithCard', 'WxController@payWithCard')->name('payWithCard');
    Route::any('/accountRecords', 'WxController@accountRecords')->name('accountRecords');
    Route::any('/getOrderDetail', 'WxController@getOrderDetail')->name('getOrderDetail');
    Route::any('/getGoodInfo', 'WxController@getGoodInfo')->name('getGoodInfo');
    Route::any('/dietReport', 'WxController@dietReport')->name('dietReport');
    Route::any('/userLogin', 'WxController@userLogin')->name('userLogin');
    Route::any('/getShopOrder', 'WxController@getShopOrder')->name('getShopOrder');
    Route::any('/changeOrderState', 'WxController@changeOrderState')->name('changeOrderState');

    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'WxController@index')->name('index');

    });
});