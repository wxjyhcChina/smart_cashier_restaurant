<?php

namespace App\Modules\Services\Account;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider.
 */
class AccountServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('account', 'App\Modules\Services\Account\Account');
    }
}
