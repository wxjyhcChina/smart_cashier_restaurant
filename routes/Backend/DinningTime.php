<?php
Route::group([
    'namespace' => 'DinningTime',
    'middleware' => 'access.routeNeedsPermission:manage-dinning-time',
], function() {
    Route::resource('dinningTime', 'DinningTimeController', ['except' => ['show', 'edit']]);

    Route::get('dinningTime/get', 'DinningTimeTableController')->name('dinningTime.get');

    Route::group([
        'prefix' => 'dinningTime/{dinningTime}',
        'middleware' => 'can:view,dinningTime'
    ], function() {
        Route::get('edit', 'DinningTimeController@edit')->name('dinningTime.edit');
        Route::get('mark/{status}', 'DinningTimeController@mark')->name('dinningTime.mark')->where(['status' => '[0,1]']);
    });
});