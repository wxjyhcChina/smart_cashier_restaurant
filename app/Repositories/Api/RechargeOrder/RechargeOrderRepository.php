<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\RechargeOrder;

use App\Modules\Repositories\RechargeOrder\BaseRechargeOrderRepository;

/**
 * Class CustomerRepository
 * @package App\Repositories\Backend\Customer
 */
class RechargeOrderRepository extends BaseRechargeOrderRepository
{
    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurant($restaurant_id)
    {
        return $this->query()
            ->where('restaurant_id', $restaurant_id)
            ->get();
    }
}