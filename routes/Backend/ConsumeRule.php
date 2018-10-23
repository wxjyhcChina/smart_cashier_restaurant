<?php
Route::group([
    'namespace' => 'ConsumeRule',
    'middleware' => 'access.routeNeedsPermission:manage-consume-rule',
], function() {
    Route::resource('consumeRule', 'ConsumeRuleController', ['except' => ['show', 'edit']]);

    Route::get('consumeRule/get', 'ConsumeRuleTableController')->name('consumeRule.get');

    Route::group([
        'prefix' => 'consumeRule/{consumeRule}',
        'middleware' => 'can:view,consumeRule'
    ], function() {
        Route::get('edit', 'ConsumeRuleController@edit')->name('consumeRule.edit');
        Route::get('mark/{status}', 'ConsumeRuleController@mark')->name('consumeRule.mark')->where(['status' => '[0,1]']);
    });
});