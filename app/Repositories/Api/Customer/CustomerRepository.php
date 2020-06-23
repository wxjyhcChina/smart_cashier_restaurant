<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\Customer;

use App\Modules\Repositories\Customer\BaseCustomerRepository;

/**
 * Class CustomerRepository
 * @package App\Repositories\Backend\Customer
 */
class CustomerRepository extends BaseCustomerRepository
{
    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurant($restaurant_id)
    {
        return $this->query()->with('card')
            ->with('department')
            ->with('consume_category')
            ->where('restaurant_id', $restaurant_id)
            ->get();
    }

    public function getByShop($shop_id)
    {
        return $this->query()->with('card')
            ->with('department')
            ->with('consume_category')
            ->where('shop_id', $shop_id)
            ->get();
    }
}