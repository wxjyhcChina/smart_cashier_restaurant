<?php

namespace App\Modules\Services\Card;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider.
 */
class CardServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('cardService', 'App\Modules\Services\Card\CardService');
    }
}
