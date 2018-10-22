<?php
Route::group([
    'namespace' => 'PayMethod',
    'middleware' => 'access.routeNeedsPermission:manage-pay-method',
], function() {
    Route::resource('payMethod', 'PayMethodController', ['except' => ['show', 'edit']]);

    Route::get('payMethod/get', 'PayMethodTableController')->name('payMethod.get');

    Route::group([
        'prefix' => 'payMethod/{payMethod}',
        'middleware' => 'can:view,payMethod'
    ], function() {
        Route::get('edit', 'PayMethodController@edit')->name('payMethod.edit');
        Route::get('mark/{status}', 'PayMethodController@mark')->name('payMethod.mark')->where(['status' => '[0,1]']);
    });
});