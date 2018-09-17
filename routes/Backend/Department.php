<?php
Route::group([
    'namespace' => 'department',
    'middleware' => 'access.routeNeedsPermission:manage-department',
], function() {
    Route::resource('department', 'DepartmentController', ['except' => ['show']]);

    Route::get('department/get', 'DepartmentTableController')->name('department.get');

    Route::group([
        'prefix' => 'department/{department}',
        'middleware' => 'can:view,department'
    ], function() {
        Route::get('edit', 'DepartmentController@edit')->name('department.edit');
        Route::get('mark/{status}', 'DepartmentController@mark')->name('department.mark')->where(['status' => '[0,1]']);
    });
});