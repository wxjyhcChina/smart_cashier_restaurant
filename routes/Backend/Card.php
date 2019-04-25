<?php
Route::group([
    'namespace' => 'Card',
    'middleware' => 'access.routeNeedsPermission:manage-card',
], function() {
    Route::resource('card', 'CardController', ['except' => ['show', 'edit']]);

    Route::get('card/get', 'CardTableController')->name('card.get');

    Route::get('card/getByInternalNumber', 'CardController@getByInternalNumber')->name('card.getByInternalNumber');

    Route::group([
        'prefix' => 'card/{card}',
        'middleware' => 'can:view,card'
    ], function() {
        Route::get('edit', 'CardController@edit')->name('card.edit');
    });
});