<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\Goods;

use App\Modules\Models\Label\LabelCategory;
use App\Modules\Models\Materials\Materials;
use App\Modules\Repositories\Goods\BaseGoodsRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class ShopRepository
 * @package App\Repositories\Backend\Shop
 */
class GoodsRepository extends BaseGoodsRepository
{

    public function getByRestaurantWithRelationQuery($restaurant_id)
    {
        return $this->query()
            ->select('goods.*', 'shops.name as shop_name')
            ->leftJoin('shops', 'shops.id', '=', 'goods.shop_id')
            ->where('goods.restaurant_id', $restaurant_id)
            //->where('is_temp', 0)
            ->whereNotIn('is_temp', [1])//非临时商品
            ->with('dinning_time');
    }

    public function getByShopWithRelationQuery($shop_id)
    {
        return $this->query()
            ->select('goods.*', 'shops.name as shop_name')
            ->leftJoin('shops', 'shops.id', '=', 'goods.shop_id')
            ->where('goods.shop_id', $shop_id)
            //->where('is_temp', 0)
            ->whereNotIn('is_temp', [1])//非临时商品
            ->with('dinning_time');
    }


    /**
     * @param $goods
     * @param $input
     * @throws \Throwable
     */
    public function assignLabelCategories($goods, $input)
    {
        $goods->label_categories()->detach();
        Log::info("label_categories:".json_encode($goods));
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

    public function assignMaterialCategories($goods, $input)
    {
        $goods->materials()->detach();
        Log::info("materials:".json_encode($goods));
        Log::info("input:".json_encode($input));

        if(array_key_exists('id', $input))
        {
            $ids = $input['id'];

            foreach ($ids as $id)
            {
                $material = Materials::find($id);
                //Log::info("material:".json_encode($material));
                $idz=$material->id;
                //Log::info("number:".$idz);
                $count=$input["number".$idz];
                //Log::info("count:".json_encode($count));
                $this->bindMaterialCategory($goods, $material, $count);
            }
        }
    }
}