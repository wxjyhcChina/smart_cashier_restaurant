<?php

Route::group([
    'prefix'  => 'users',
    'as' => 'users.'
], function() {
    Route::post('/login', 'UserController@login')->name('login');
    Route::get('/refreshToken', 'UserController@refreshToken')->name('refreshToken');
    Route::get('/validateToken', 'UserController@validateToken')->name('validateToken');

    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/current', 'UserController@current')->name('current');
        Route::post('/logout', 'UserController@logout')->name('logout');
    });
});