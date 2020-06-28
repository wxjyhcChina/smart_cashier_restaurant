<?php
Route::group([
    'namespace' => 'GoodCategory',
    'middleware' => 'access.routeNeedsPermission:manage-goods',
], function() {
    Route::resource('goodCategory', 'GoodCategoryController', ['except' => ['show', 'edit', 'update', 'destroy']]);

    Route::get('goodCategory/get', 'GoodCategoryTableController')->name('goodCategory.get');

    Route::group([
        'prefix' => 'goodCategory/{goodCategory}',
        'middleware' => 'can:view,goodCategory'
    ], function() {
        Route::get('edit', 'GoodCategoryController@edit')->name('goodCategory.edit');
        Route::patch('', 'GoodCategoryController@update')->name('goodCategory.update');
    });
});