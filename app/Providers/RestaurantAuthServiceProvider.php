<?php

namespace App\Providers;

use App\Modules\Models\Card\Card;
use App\Modules\Models\ConsumeCategory\ConsumeCategory;
use App\Modules\Models\ConsumeRule\ConsumeRule;
use App\Modules\Models\Customer\Customer;
use App\Modules\Models\Department\Department;
use App\Modules\Models\Device\Device;
use App\Modules\Models\DinningTime\DinningTime;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Label\LabelCategory;
use App\Modules\Models\PayMethod\PayMethod;
use App\Modules\Models\Shop\Shop;
use App\Policies\CardPolicy;
use App\Policies\ConsumeCategoryPolicy;
use App\Policies\ConsumeRulePolicy;
use App\Policies\CustomerPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\DevicePolicy;
use App\Policies\DinningTimePolicy;
use App\Policies\GoodsPolicy;
use App\Policies\LabelCategoryPolicy;
use App\Policies\PayMethodPolicy;
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
        Shop::class => ShopPolicy::class,
        DinningTime::class => DinningTimePolicy::class,
        ConsumeCategory::class => ConsumeCategoryPolicy::class,
        Customer::class => CustomerPolicy::class,
        PayMethod::class => PayMethodPolicy::class,
        Goods::class => GoodsPolicy::class,
        LabelCategory::class => LabelCategoryPolicy::class,
        ConsumeRule::class => ConsumeRulePolicy::class,
    ];
}
