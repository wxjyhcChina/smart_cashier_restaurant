<?php
Route::group([
    'namespace' => 'Goods',
    'middleware' => 'access.routeNeedsPermission:manage-goods',
], function() {
    Route::resource('goods', 'GoodsController', ['except' => ['show', 'edit', 'update', 'destroy']]);

    Route::get('goods/get', 'GoodsTableController')->name('goods.get');
    Route::get('goods/getLabelCategories', 'GoodsTableController@getLabelCategories')->name('goods.getLabelCategories');
    Route::post('uploadImage', 'GoodsController@uploadImage')->name('goods.uploadImage');

    Route::group([
        'prefix' => 'goods/{goods}',
        'middleware' => 'can:view,goods'
    ], function() {
        Route::get('edit', 'GoodsController@edit')->name('goods.edit');
        Route::get('info', 'GoodsController@show')->name('goods.info');
        Route::patch('', 'GoodsController@update')->name('goods.update');
        Route::delete('', 'GoodsController@destroy')->name('goods.destroy');

        Route::get('labelCategories/create', 'GoodsController@assignLabelCategory')->name('goods.assignLabelCategory');
        Route::post('labelCategories/store', 'GoodsController@assignLabelCategoryStore')->name('goods.assignLabelCategoryStore');

    });
});