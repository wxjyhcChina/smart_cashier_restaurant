<?php

namespace App\Modules\Repositories\Goods;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Label\Label;
use App\Modules\Models\Shop\Shop;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseGoodsRepository.
 */
class BaseGoodsRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Goods::class;

    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()
            ->where('restaurant_id', $restaurant_id)
            ->where('is_temp', 0);
    }


    /**
     * @param Goods $goods
     * @return Goods
     */
    public function getGoodsInfo(Goods $goods)
    {
        return $goods->load('shop', 'dinning_time', 'label_categories');
    }

    /**
     * @param Goods $goods
     * @return mixed
     */
    public function getLabelCategories(Goods $goods)
    {
        return $goods->label_categories;
    }

    /**
     * @param $goods
     * @param $labelCategory
     */
    protected function detachLabelCategory($goods, $labelCategory)
    {
        $labelCategory->goods()->detach($goods->id);
    }

    /**
     * @param $goods
     * @param $labelCategory
     * @param bool $overwrite
     * @return mixed
     * @throws ApiException
     */
    protected function bindLabelCategory($goods, $labelCategory, $overwrite = false)
    {
        try
        {
            DB::beginTransaction();

            //一个用餐时间一种盘子只能绑定一种商品
            $goodsDinningTime = $goods->dinning_time->pluck('id');
            $existingGoods = $labelCategory->goods()->whereHas('dinning_time', function ($query) use ($goodsDinningTime){
                $query->whereIn('dinning_time_id', $goodsDinningTime);
            })->get();

            $shouldAttach = true;
            foreach ($existingGoods as $temp)
            {
                if ($temp->id == $goods->id)
                {
                    $shouldAttach = false;
                   continue;
                }
                else if ($overwrite == false)
                {
                    throw new ApiException(ErrorCode::LABEL_CATEGORY_ALREADY_BINDED, trans('api.error.label_category_already_binded'));
                }
                else
                {
                    $labelCategory->goods()->detach($temp->id);
                }
            }

            if ($shouldAttach)
            {
                $labelCategory->goods()->attach($goods->id);
            }

            DB::commit();
        }
        catch (\Exception $exception)
        {
            DB::rollBack();

            if ($exception instanceof ApiException)
            {
                throw $exception;
            }

            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.goods.bind_error'));
        }

        return $labelCategory;
    }

    /**
     * @param $goods
     * @param $label
     * @param bool $overwrite
     * @return mixed
     * @throws ApiException
     */
    public function storeLabelCategory($goods, $label, $overwrite = false)
    {
        $label = Label::where('rfid', $label)->first();

        if ($label == null)
        {
            throw new ApiException(ErrorCode::LABEL_NOT_EXIST, trans('api.error.label_not_exist'));
        }

        $labelCategory = $label->label_category;
        if ($labelCategory == null)
        {
            throw new ApiException(ErrorCode::LABEL_CATEGORY_NOT_BINDED, trans('api.error.label_category_not_binded'));
        }
        else if ($labelCategory->restaurant_id != $goods->restaurant_id)
        {
            throw new ApiException(ErrorCode::LABEL_CATEGORY_BIND_ON_OTHER_RESTAURANT, trans('api.error.label_category_bind_on_other_restaurant'));
        }

        $labelCategory = $this->bindLabelCategory($goods, $labelCategory, $overwrite);

        return $labelCategory->load('goods');
    }
    

    /**
     * @param $input
     * @return Goods
     * @throws ApiException
     */
    public function create($input)
    {
        //TODO: check shop/dinning_time
        $goods = $this->createGoodsStub($input);

        if ($goods->save())
        {
            if (isset($input['dinning_time']))
            {
                $goods->dinning_time()->attach($input['dinning_time']);
            }

            return $goods->load('shop', 'dinning_time');
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.goods.create_error'));
    }

    /**
     * @param Goods $goods
     * @param $input
     * @return Goods
     * @throws ApiException
     */
    public function update(Goods $goods, $input)
    {
        Log::info("goods update param:".json_encode($input));

        try
        {
            DB::beginTransaction();
            $goods->update($input);

            if (isset($input['dinning_time'])){

                $labelCategories = $goods->label_categories;
                foreach ($labelCategories as $labelCategory)
                {
                    $existingGoods = $labelCategory->goods()->whereHas('dinning_time', function ($query) use ($input){
                        $query->whereIn('dinning_time_id', $input['dinning_time']);
                    })->get();

                    foreach ($existingGoods as $temp)
                    {
                        if ($temp->id == $goods->id)
                        {
                            continue;
                        }
                        else
                        {
                            throw new ApiException(ErrorCode::GOODS_DINNING_TIME_BIND_LABEL_CATEGORY_CONFLICT, trans('api.error.goods_dinning_time_bind_label_category_conflict'));
                        }
                    }
                }

                $goods->dinning_time()->detach();
                $goods->dinning_time()->attach($input['dinning_time']);
            }

            DB::commit();

            return $goods->load('shop', 'dinning_time');
        }
        catch (\Exception $exception)
        {
            DB::rollBack();

            if ($exception instanceof ApiException)
            {
                throw $exception;
            }
            else
            {
                throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.goods.update_error'));
            }
        }
    }

    /**
     * @param Goods $goods
     * @return bool
     * @throws \Exception
     */
    public function delete(Goods $goods)
    {
        $goods->delete();

        return true;
    }

    /**
     * @param $input
     * @return Goods
     * @throws ApiException
     */
    private function createGoodsStub($input)
    {
        if (isset($input['is_temp']) && $input['is_temp'] == 1)
        {
            $input['name'] = '临时商品';
            $shop = Shop::where('default', true)
                ->where('restaurant_id', $input['restaurant_id'])
                ->first();

            if ($shop == null)
            {
                throw new ApiException(ErrorCode::NO_DEFAULT_SHOP, trans('api.error.no_default_shop'));
            }
            $input['shop_id'] = $shop->id;
        }

        $goods = new Goods();
        $goods->restaurant_id = $input['restaurant_id'];
        $goods->shop_id = $input['shop_id'];
        $goods->name = $input['name'];
        $goods->price = $input['price'];
        $goods->image = isset($input['image']) ? $input['image'] : '';
        $goods->is_temp = isset($input['is_temp']) ? $input['is_temp'] : 0;

        return $goods;
    }
}
