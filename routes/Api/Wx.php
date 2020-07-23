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
    Route::post('/create', 'WxController@create')->name('create');
    Route::post('/pay', 'WxController@pay')->name('pay');
    Route::post('/payWithCard', 'WxController@payWithCard')->name('payWithCard');

    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'WxController@index')->name('index');

    });
});