<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\DinningTime;

use App\Modules\Repositories\DinningTime\BaseDinningTimeRepository;

/**
 * Class DinningTimeRepository
 * @package App\Repositories\Backend\DinningTime
 */
class DinningTimeRepository extends BaseDinningTimeRepository
{
    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurant($restaurant_id)
    {
        return $this->getByRestaurantQuery($restaurant_id)->get();
    }

    public function getByShop($shop_id)
    {
        return $this->getByShopQuery($shop_id)->get();
    }
}