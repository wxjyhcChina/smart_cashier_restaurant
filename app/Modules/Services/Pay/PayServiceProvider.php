<?php

namespace App\Modules\Services\Pay;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider.
 */
class PayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('pay', 'App\Modules\Services\Pay\Pay');
    }
}
