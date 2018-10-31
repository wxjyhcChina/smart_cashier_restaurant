<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\ConsumeOrder;

use App\Modules\Repositories\ConsumeOrder\BaseConsumeOrderRepository;

/**
 * Class ConsumeOrderRepository
 * @package App\Repositories\Backend\ConsumeOrder
 */
class ConsumeOrderRepository extends BaseConsumeOrderRepository
{
    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurantWithRelationQuery($restaurant_id)
    {
        return $this->getByRestaurantQuery($restaurant_id)->select('consume_orders.*', 'customers.user_name as customer_name', 'cards.number as card_number', 'dinning_time.name as dinning_time_name', 'restaurant_users.username as restaurant_user_name')
            ->leftJoin('customers', 'consume_orders.customer_id', '=', 'customers.id')
            ->leftJoin('cards', 'consume_orders.card_id', '=', 'cards.id')
            ->leftJoin('dinning_time', 'consume_orders.dinning_time_id', '=', 'dinning_time.id')
            ->leftJoin('restaurant_users', 'consume_orders.restaurant_user_id', '=', 'restaurant_users.id');
    }
}