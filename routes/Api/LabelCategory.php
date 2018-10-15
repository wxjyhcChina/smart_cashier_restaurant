<?php

Route::group([
    'prefix'  => 'labelCategories',
    'as' => 'labelCategories.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'LabelCategoryController@index')->name('index');
        Route::get('/{labelCategory}/labels', 'LabelCategoryController@getLabels')->name('getLabels');
    });
});