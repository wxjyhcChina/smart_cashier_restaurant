<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\Goods;

use App\Modules\Models\Label\LabelCategory;
use App\Modules\Repositories\Goods\BaseGoodsRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class ShopRepository
 * @package App\Repositories\Backend\Shop
 */
class GoodsRepository extends BaseGoodsRepository
{

    public function getByRestaurantWithRelationQuery($restaurant_id)
    {
        return $this->query()
            ->select('goods.*', 'shops.name as shop_name', 'dinning_time.name as dinning_time_name')
            ->leftJoin('shops', 'shops.id', '=', 'goods.shop_id')
            ->leftJoin('dinning_time', 'dinning_time.id', '=', 'goods.dinning_time_id')
            ->where('goods.restaurant_id', $restaurant_id)
            ->where('is_temp', 0);
    }


    /**
     * @param $goods
     * @param $input
     * @throws \Throwable
     */
    public function assignLabelCategories($goods, $input)
    {
        $goods->label_categories()->detach();

        if(array_key_exists('id', $input))
        {
            $ids = $input['id'];

            foreach ($ids as $id)
            {
                $labelCategory = LabelCategory::find($id);
                $this->bindLabelCategory($goods, $labelCategory, true);
            }
        }
    }
}