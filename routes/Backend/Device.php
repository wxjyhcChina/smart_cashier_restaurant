<?php
Route::group([
    'namespace' => 'device',
    'middleware' => 'access.routeNeedsPermission:manage-device',
], function() {
    Route::resource('device', 'DeviceController', ['except' => ['show']]);

    Route::post('device/import', 'DeviceController@import')->name('device.import');
    Route::get('device/get', 'DeviceTableController')->name('device.get');

    Route::group([
        'prefix' => 'device/{device}',
        'middleware' => 'can:view,device'
    ], function() {
        Route::get('edit', 'DeviceController@edit')->name('device.edit');
        Route::get('mark/{status}', 'DeviceController@mark')->name('device.mark')->where(['status' => '[0,1]']);
    });
});