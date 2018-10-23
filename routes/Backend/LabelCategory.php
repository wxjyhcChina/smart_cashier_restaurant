<?php
Route::group([
    'namespace' => 'LabelCategory',
    'middleware' => 'access.routeNeedsPermission:manage-label-category',
], function() {
    Route::resource('labelCategory', 'LabelCategoryController', ['except' => ['show', 'edit']]);

    Route::get('labelCategory/get', 'LabelCategoryTableController')->name('labelCategory.get');

    Route::group([
        'prefix' => 'labelCategory/{labelCategory}',
        'middleware' => 'can:view,labelCategory'
    ], function() {

    });
});