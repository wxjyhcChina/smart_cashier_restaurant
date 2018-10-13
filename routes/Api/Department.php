<?php

Route::group([
    'prefix'  => 'departments',
    'as' => 'departments.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'DepartmentController@index')->name('index');
    });
});