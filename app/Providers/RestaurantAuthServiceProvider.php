<?php

namespace App\Providers;

use App\Modules\Models\Card\Card;
use App\Modules\Models\Department\Department;
use App\Modules\Models\Device\Device;
use App\Modules\Models\Shop\Shop;
use App\Policies\CardPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\DevicePolicy;
use App\Policies\ShopPolicy;

/**
 * Class RestaurantAuthServiceProvider.
 */
class RestaurantAuthServiceProvider extends AuthServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
        Card::class => CardPolicy::class,
        Device::class => DevicePolicy::class,
        Department::class => DepartmentPolicy::class,
        Shop::class => ShopPolicy::class
    ];
}
