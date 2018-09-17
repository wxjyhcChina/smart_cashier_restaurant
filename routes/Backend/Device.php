<?php
Route::group([
    'namespace' => 'device',
    'middleware' => 'access.routeNeedsPermission:manage-device',
], function() {
    Route::resource('device', 'DeviceController', ['except' => ['show']]);

    Route::get('device/get', 'DeviceTableController')->name('device.get');

    Route::group([
        'prefix' => 'device/{device}',
        'middleware' => 'can:view,device'
    ], function() {

    });
});