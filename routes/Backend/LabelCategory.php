<?php
Route::group([
    'namespace' => 'LabelCategory',
    'middleware' => 'access.routeNeedsPermission:manage-label-category',
], function() {
    Route::resource('labelCategory', 'LabelCategoryController', ['except' => ['show', 'edit']]);

    Route::get('labelCategory/get', 'LabelCategoryTableController')->name('labelCategory.get');
    Route::get('labelCategory/getLabels', 'LabelCategoryTableController@getLabels')->name('labelCategory.getLabels');
    Route::post('labelCategory/uploadImage', 'LabelCategoryController@uploadImage')->name('labelCategory.uploadImage');

    Route::group([
        'prefix' => 'labelCategory/{labelCategory}',
        'middleware' => 'can:view,labelCategory'
    ], function() {
        Route::get('edit', 'LabelCategoryController@edit')->name('labelCategory.edit');
        Route::get('info', 'LabelCategoryController@show')->name('labelCategory.info');

        Route::get('labels/create', 'LabelCategoryController@assignLabel')->name('labelCategory.assignLabel');
        Route::post('labels/store', 'LabelCategoryController@assignLabelStore')->name('labelCategory.assignLabelStore');
    });
});