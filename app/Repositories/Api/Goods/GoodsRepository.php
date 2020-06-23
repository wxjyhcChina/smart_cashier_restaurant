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
    public function getByRestaurant($restaurant_id, $input, $size=15)
    {
        $query = $this->getByRestaurantQuery($restaurant_id)
            ->with('shop')
            ->with('dinning_time')
            ->with('label_categories');

        if (isset($input['key']))
        {
            $query->where(function ($query) use ($input){
                $query->where('id', 'like', '%'.$input['key'].'%')
                    ->orWhere('price', 'like', '%'.$input['key'].'%')
                    ->orWhere('name', 'like', '%'.$input['key'].'%')
                    ->orWhereHas('dinning_time', function ($query) use ($input){
                        $query->where("name", 'like', '%'.$input['key'].'%');
                    })->orWhereHas('shop', function ($query) use ($input){
                        $query->where('name', 'like', '%'.$input['key'].'%');
                    });
            });
        }


        return $query->paginate($size);
    }

    public function getByShop($shop_id, $input, $size=15)
    {
        $query = $this->getByRestaurantQuery($shop_id)
            ->with('shop')
            ->with('dinning_time')
            ->with('label_categories');

        if (isset($input['key']))
        {
            $query->where(function ($query) use ($input){
                $query->where('id', 'like', '%'.$input['key'].'%')
                    ->orWhere('price', 'like', '%'.$input['key'].'%')
                    ->orWhere('name', 'like', '%'.$input['key'].'%')
                    ->orWhereHas('dinning_time', function ($query) use ($input){
                        $query->where("name", 'like', '%'.$input['key'].'%');
                    })->orWhereHas('shop', function ($query) use ($input){
                        $query->where('name', 'like', '%'.$input['key'].'%');
                    });
            });
        }


        return $query->paginate($size);
    }

    public function getTableByRestaurant($restaurant_id, $input, $size=15)
    {
        $query = $this->getTableFoodByRestaurantQuery($restaurant_id)
            ->with('shop')
            ->with('dinning_time')
            ->with('label_categories');

        if (isset($input['key']))
        {
            $query->where(function ($query) use ($input){
                $query->where('id', 'like', '%'.$input['key'].'%')
                    ->orWhere('price', 'like', '%'.$input['key'].'%')
                    ->orWhere('name', 'like', '%'.$input['key'].'%')
                    ->orWhereHas('dinning_time', function ($query) use ($input){
                        $query->where("name", 'like', '%'.$input['key'].'%');
                    })->orWhereHas('shop', function ($query) use ($input){
                        $query->where('name', 'like', '%'.$input['key'].'%');
                    });
            });
        }


        return $query->paginate($size);
    }

    public function getTableByShop($shop_id, $input, $size=15)
    {
        $query = $this->getTableFoodByShopQuery($shop_id)
            ->with('shop')
            ->with('dinning_time')
            ->with('label_categories');

        if (isset($input['key']))
        {
            $query->where(function ($query) use ($input){
                $query->where('id', 'like', '%'.$input['key'].'%')
                    ->orWhere('price', 'like', '%'.$input['key'].'%')
                    ->orWhere('name', 'like', '%'.$input['key'].'%')
                    ->orWhereHas('dinning_time', function ($query) use ($input){
                        $query->where("name", 'like', '%'.$input['key'].'%');
                    })->orWhereHas('shop', function ($query) use ($input){
                        $query->where('name', 'like', '%'.$input['key'].'%');
                    });
            });
        }


        return $query->paginate($size);
    }
}