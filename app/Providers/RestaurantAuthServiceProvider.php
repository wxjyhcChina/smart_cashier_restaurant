<?php

namespace App\Providers;

use App\Modules\Models\Card\Card;
use App\Modules\Models\Department\Department;
use App\Modules\Models\Device\Device;
use App\Policies\CardPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\DevicePolicy;

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
        Department::class => DepartmentPolicy::class
    ];
}
