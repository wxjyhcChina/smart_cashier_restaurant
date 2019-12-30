<?php

namespace App\Modules\Repositories\ConsumeOrder;

use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class BaseConsumeOrderRepository.
 */
class BaseConsumeOrderRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = ConsumeOrder::class;


    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()
            ->where('consume_orders.restaurant_id', $restaurant_id)
            ->with('goods')
            ->with('customer')
            ->with('card')
            ->with('dinning_time');
    }

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
            DB::raw('concat(restaurant_users.last_name, restaurant_users.first_name) as restaurant_user_name'),
            'restaurant_users.last_name as restaurant_last_name',
            'restaurant_users.first_name as restaurant_first_name',
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
