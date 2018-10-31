<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\RechargeOrder;

use App\Modules\Repositories\RechargeOrder\BaseRechargeOrderRepository;

/**
 * Class RechargeOrderRepository
 * @package App\Repositories\Backend\RechargeOrder
 */
class RechargeOrderRepository extends BaseRechargeOrderRepository
{
    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurantWithRelationQuery($restaurant_id)
    {
        return $this->getByRestaurantQuery($restaurant_id)->select('recharge_orders.*', 'customers.user_name as customer_name', 'cards.number as card_number', 'restaurant_users.username as restaurant_user_name')
            ->leftJoin('customers', 'recharge_orders.customer_id', '=', 'customers.id')
            ->leftJoin('cards', 'recharge_orders.card_id', '=', 'cards.id')
            ->leftJoin('restaurant_users', 'recharge_orders.restaurant_user_id', '=', 'restaurant_users.id');
    }
}