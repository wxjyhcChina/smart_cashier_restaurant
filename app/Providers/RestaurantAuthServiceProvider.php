<?php

namespace App\Providers;

use App\Modules\Models\Card\Card;
use App\Modules\Models\Device\Device;
use App\Policies\CardPolicy;
use App\Policies\DevicePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class RestaurantAuthServiceProvider.
 */
class RestaurantAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
        Card::class => CardPolicy::class,
        Device::class => DevicePolicy::class
    ];
}
