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
     * @param $start_time
     * @param $end_time
     * @param null $dinning_time_id
     * @param null $pay_method
     * @param null $restaurant_user_id
     * @return mixed
     */
    public function getByRestaurantWithRelationQuery($restaurant_id, $start_time, $end_time, $dinning_time_id = null, $pay_method = null, $restaurant_user_id= null, $status = [], $departmentOnly = false, $consumeCategoryOnly=false)
    {
        $query = $this->getByRestaurantQuery($restaurant_id)->select(
                'consume_orders.*',
                'customers.user_name as customer_name',
                'cards.number as card_number',
                'dinning_time.name as dinning_time_name',
                'restaurant_users.username as restaurant_user_name',
                'departments.name as department_name',
                'consume_categories.name as consume_category_name'
            )
            ->leftJoin('customers', 'consume_orders.customer_id', '=', 'customers.id')
            ->leftJoin('cards', 'consume_orders.card_id', '=', 'cards.id')
            ->leftJoin('dinning_time', 'consume_orders.dinning_time_id', '=', 'dinning_time.id')
            ->leftJoin('restaurant_users', 'consume_orders.restaurant_user_id', '=', 'restaurant_users.id')
            ->leftJoin('departments', 'consume_orders.department_id', '=', 'departments.id')
            ->leftJoin('consume_categories', 'consume_orders.consume_category_id', '=', 'consume_categories.id')
            ->where('consume_orders.created_at', '>=', $start_time)
            ->where('consume_orders.created_at', '<=', $end_time);

        if ($dinning_time_id != null)
        {
            $query->where('consume_orders.dinning_time_id', $dinning_time_id);
        }

        if ($pay_method != null)
        {
            $query->where('consume_orders.pay_method', $pay_method);
        }

        if ($restaurant_user_id != null)
        {
            $query->where('consume_orders.restaurant_user_id', $restaurant_user_id);
        }

        if (!empty($status))
        {
            $query->whereIn('consume_orders.status', $status);
        }

        if ($departmentOnly)
        {
            $query->whereNotNull('consume_orders.department_id');
        }

        if ($consumeCategoryOnly)
        {
            $query->whereNotNull('consume_orders.consume_category_id');
        }

        return $query;
    }
}