<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\Shop;

use App\Modules\Repositories\Shop\BaseShopRepository;

/**
 * Class ShopRepository
 * @package App\Repositories\Backend\Shop
 */
class ShopRepository extends BaseShopRepository
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