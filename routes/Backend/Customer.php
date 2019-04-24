<?php
Route::group([
    'namespace' => 'Customer',
    'middleware' => 'access.routeNeedsPermission:manage-customer',
], function() {
    Route::resource('customer', 'CustomerController', ['except' => ['show']]);

    Route::get('customer/get', 'CustomerTableController')->name('customer.get');
    Route::get('customer/availableCard', 'CustomerTableController@availableCard')->name('customer.availableCard');
    Route::get('changeMultipleBalance', 'CustomerController@changeMultipleBalance')->name('customer.changeMultipleBalance');
    Route::post('changeMultipleBalanceStore', 'CustomerController@changeMultipleBalanceStore')->name('customer.changeMultipleBalanceStore');
    Route::get('clearSubsidyBalance', 'CustomerController@clearSubsidyBalance')->name('customer.clearSubsidyBalance');
    Route::post('clearSubsidyBalanceStore', 'CustomerController@clearSubsidyBalanceStore')->name('customer.clearSubsidyBalanceStore');

    Route::group([
        'prefix' => 'customer/{customer}',
        'middleware' => 'can:view,customer'
    ], function() {
        Route::get('edit', 'CustomerController@edit')->name('customer.edit');
        Route::get('mark/{status}', 'CustomerController@mark')->name('customer.mark')->where(['status' => '[0,1]']);
        Route::get('accountRecords', 'CustomerController@accountRecords')->name('customer.accountRecords');
        Route::get('accountRecords/get', 'CustomerTableController@getAccountRecords')->name('customer.getAccountRecords');
        Route::get('consumeOrders', 'CustomerController@consumeOrders')->name('customer.consumeOrders');
        Route::get('bindCard', 'CustomerController@bindCard')->name('customer.bindCard');
        Route::patch('doBindCard', 'CustomerController@doBindCard')->name('customer.doBindCard');
        Route::get('unbindCard', 'CustomerController@unbindCard')->name('customer.unbindCard');
        Route::patch('doUnbindCard', 'CustomerController@doUnbindCard')->name('customer.doUnbindCard');
        Route::get('lostCard', 'CustomerController@lostCard')->name('customer.lostCard');
        Route::patch('doLostCard', 'CustomerController@doLostCard')->name('customer.doLostCard');
        Route::get('consumeOrders/get', 'CustomerTableController@getConsumeOrders')->name('customer.getConsumeOrders');
        Route::get('changeBalance', 'CustomerController@changeBalance')->name('customer.changeBalance');
        Route::post('changeBalanceStore', 'CustomerController@changeBalanceStore')->name('customer.changeBalanceStore');
        Route::get('recharge', 'CustomerController@recharge')->name('customer.recharge');
        Route::post('rechargeAndPay', 'CustomerController@rechargeAndPay')->name('customer.rechargeAndPay');
    });
});