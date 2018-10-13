<?php

Route::group([
    'prefix'  => 'customers',
    'as' => 'customers.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'CustomerController@index')->name('index');
        Route::post('/', 'CustomerController@create')->name('create');
        Route::put('/{customer}', 'CustomerController@update')->name('update');
    });
});