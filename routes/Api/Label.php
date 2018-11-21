<?php

Route::group([
    'prefix'  => 'labels',
    'as' => 'labels.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'LabelController@index')->name('index');
    });
});