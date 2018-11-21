<?php

Route::group([
    'prefix'  => 'labelCategories',
    'as' => 'labelCategories.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'LabelCategoryController@index')->name('index');
        Route::get('/imageUploadToken', 'LabelCategoryController@imageUploadToken')->name('imageUploadToken');
        Route::post('/', 'LabelCategoryController@store')->name('store');
        Route::put('/{labelCategory}', 'LabelCategoryController@update')->name('update');

        Route::get('/{labelCategory}/labels', 'LabelCategoryController@getLabels')->name('getLabels');
        Route::post('/{labelCategory}/labels', 'LabelCategoryController@storeLabels')->name('storeLabels');
    });
});