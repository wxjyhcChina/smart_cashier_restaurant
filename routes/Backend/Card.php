<?php
Route::group([
    'namespace' => 'Card',
    'middleware' => 'access.routeNeedsPermission:manage-card',
], function() {
    Route::resource('card', 'CardController', ['except' => ['show']]);

    Route::post('card/import', 'CardController@import')->name('card.import');
    Route::get('card/get', 'CardTableController')->name('card.get');

    Route::group(['prefix' => 'card/{card}'], function() {
        Route::get('mark/{status}', 'CardController@mark')->name('card.mark')->where(['status' => '[0,1]']);
        Route::get('accountRecords', 'CardController@accountRecords')->name('card.accountRecords');
        Route::get('accountRecords/get', 'CardTableController@getAccountRecords')->name('card.getAccountRecords');
        Route::get('changeBalance', 'CardController@changeBalance')->name('card.changeBalance');
        Route::post('changeBalanceStore', 'CardController@changeBalanceStore')->name('card.changeBalanceStore');
    });
});