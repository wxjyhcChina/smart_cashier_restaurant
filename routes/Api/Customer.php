<?php

Route::group([
    'prefix'  => 'customers',
    'as' => 'customers.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'CustomerController@index')->name('index');
        Route::post('/', 'CustomerController@store')->name('store');
        Route::put('/{customer}', 'CustomerController@update')->name('update');
        Route::get('/{customer}/accountRecords', 'CustomerController@accountRecords')->name('accountRecords');
    });
});