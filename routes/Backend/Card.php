<?php
Route::group([
    'namespace' => 'Card',
    'middleware' => 'access.routeNeedsPermission:manage-card',
], function() {
    Route::resource('card', 'CardController', ['except' => ['show', 'edit']]);

    Route::post('card/import', 'CardController@import')->name('card.import');
    Route::get('card/get', 'CardTableController')->name('card.get');

    Route::group([
        'prefix' => 'card/{card}',
        'middleware' => 'can:view,card'
    ], function() {
        Route::get('edit', 'CardController@edit')->name('card.edit');
        Route::get('mark/{status}', 'CardController@mark')->name('card.mark')->where(['status' => '[0,1]']);
    });
});