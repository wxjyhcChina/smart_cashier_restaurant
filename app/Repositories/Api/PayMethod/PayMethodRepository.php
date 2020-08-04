<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\PayMethod;

use App\Modules\Repositories\PayMethod\BasePayMethodRepository;

/**
 * Class LabelCategoryRepository
 * @package App\Repositories\Backend\Label
 */
class PayMethodRepository extends BasePayMethodRepository
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