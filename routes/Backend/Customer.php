<?php
Route::group([
    'namespace' => 'Customer',
    'middleware' => 'access.routeNeedsPermission:manage-customer',
], function() {
    Route::resource('customer', 'CustomerController', ['except' => ['show']]);

    Route::get('customer/get', 'CustomerTableController')->name('customer.get');
    Route::get('customer/availableCard', 'CustomerTableController@availableCard')->name('customer.availableCard');

    Route::group([
        'prefix' => 'customer/{customer}',
        'middleware' => 'can:view,customer'
    ], function() {
        Route::get('edit', 'CustomerController@edit')->name('customer.edit');
        Route::get('mark/{status}', 'CustomerController@mark')->name('customer.mark')->where(['status' => '[0,1]']);
    });
});