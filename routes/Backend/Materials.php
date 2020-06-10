<?php
Route::group([
    'namespace' => 'Materials',
    'middleware' => 'access.routeNeedsPermission:manage-goods',
], function() {
    Route::resource('materials', 'MaterialsController', ['except' => ['show', 'edit', 'update', 'destroy']]);

    Route::get('materials/get', 'MaterialsTableController')->name('materials.get');


    Route::group([
        'prefix' => 'goods/{goods}',
        'middleware' => 'can:view,materials'
    ], function() {

    });
});