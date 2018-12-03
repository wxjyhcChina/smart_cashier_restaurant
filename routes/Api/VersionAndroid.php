<?php

Route::group([
    'prefix'  => 'versionAndroid',
    'as' => 'versionAndroid.'
], function() {
    Route::get('/', 'VersionAndroidController@index')->name('index');
});