<?php
Route::group([
    'namespace' => 'ConsumeCategory',
    'middleware' => 'access.routeNeedsPermission:manage-consume-category',
], function() {
    Route::resource('consumeCategory', 'ConsumeCategoryController', ['except' => ['show']]);

    Route::get('consumeCategory/get', 'ConsumeCategoryTableController')->name('consumeCategory.get');

    Route::group([
        'prefix' => 'consumeCategory/{consumeCategory}',
        'middleware' => 'can:view,consumeCategory'
    ], function() {
        Route::get('edit', 'ConsumeCategoryController@edit')->name('consumeCategory.edit');
    });
});