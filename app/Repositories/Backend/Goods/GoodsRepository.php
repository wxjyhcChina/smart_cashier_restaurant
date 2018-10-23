<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\Goods;

use App\Modules\Repositories\Goods\BaseGoodsRepository;

/**
 * Class ShopRepository
 * @package App\Repositories\Backend\Shop
 */
class GoodsRepository extends BaseGoodsRepository
{

    public function getByRestaurantWithRelationQuery($restaurant_id)
    {
        return $this->query()
            ->where('restaurant_id', $restaurant_id)
            ->where('is_temp', 0)
            ->with('shop')
            ->with('dinning_time');
    }

}