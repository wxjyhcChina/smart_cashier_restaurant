<?php

Route::group([
    'prefix'  => 'consumeRules',
    'as' => 'consumeRules.'
], function() {
    Route::group([
        'middleware' => 'apiAuth'
    ], function() {
        Route::get('/', 'ConsumeRuleController@index')->name('index');
        Route::get('/{consumeRule}', 'ConsumeRuleController@get')->name('get');
        Route::post('/', 'ConsumeRuleController@store')->name('store');
        Route::put('/{consumeRule}', 'ConsumeRuleController@update')->name('update');
        Route::delete('/{consumeRule}', 'ConsumeRuleController@delete')->name('delete');
    });
});