<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\ConsumeRule;

use App\Modules\Repositories\ConsumeRule\BaseConsumeRuleRepository;

/**
 * Class CustomerRepository
 * @package App\Repositories\Backend\Customer
 */
class ConsumeRuleRepository extends BaseConsumeRuleRepository
{
    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurant($restaurant_id)
    {
        return $this->query()
            ->with('dinning_time')
            ->with('consume_categories')
            ->where('restaurant_id', $restaurant_id)
            ->get();
    }

    /**
     * @param $shop_id
     * @return mixed
     */
    public function getByShop($shop_id)
    {
        return $this->query()
            ->with('dinning_time')
            ->with('consume_categories')
            ->where('restaurant_id', $shop_id)
            ->get();
    }

}