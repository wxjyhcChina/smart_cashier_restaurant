<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Api\Goods;

use App\Modules\Models\Goods\Goods;
use App\Modules\Repositories\Goods\BaseGoodsRepository;

/**
 * Class ShopRepository
 * @package App\Repositories\Backend\Shop
 */
class GoodsRepository extends BaseGoodsRepository
{
    /**
     * @param $restaurant_id
     * @param $size
     * @return mixed
     */
    public function getByRestaurant($restaurant_id, $size=15)
    {
        return $this->getByRestaurantQuery($restaurant_id)
            ->with('shop')
            ->with('dinning_time')
            ->with('label_categories')
            ->paginate($size);
    }
}