<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\ConsumeOrder;

use App\Modules\Repositories\ConsumeOrde\BaseConsumeOrderRepository;

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
    public function getByRestaurant($restaurant_id)
    {
        return $this->getByRestaurantQuery($restaurant_id)->get();
    }
}