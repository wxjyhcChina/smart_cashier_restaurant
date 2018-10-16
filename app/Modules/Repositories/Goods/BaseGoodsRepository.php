<?php

namespace App\Modules\Repositories\Goods;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Label\Label;
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
        return $goods->load('shop', 'dinning_time');
    }

    /**
     * @param Goods $goods
     * @return mixed
     */
    public function getLabelCategories(Goods $goods)
    {
        return $goods->labelCategories;
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

        $labelCategory = $label->labelCategory;
        if ($labelCategory == null)
        {
            throw new ApiException(ErrorCode::LABEL_CATEGORY_NOT_BINDED, trans('api.error.label_category_not_binded'));
        }


        try
        {
            DB::beginTransaction();

            $existingGoods = $labelCategory->goods()->where('dinning_time_id', $goods->dinning_time_id)->first();

            if ($existingGoods != null)
            {
                if ($existingGoods->id == $goods->id)
                {
                    return $labelCategory;
                }
                else if ($overwrite == false)
                {
                    throw new ApiException(ErrorCode::LABEL_CATEGORY_ALREADY_BINDED, trans('api.error.label_category_already_binded'));
                }
                else
                {
                    $labelCategory->goods()->detach($existingGoods->id);
                }
            }

            $labelCategory->goods()->attach($goods->id);

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

            DB::commit();

            return $goods->load('shop', 'dinning_time');
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
            throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.goods.update_error'));
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
     */
    private function createGoodsStub($input)
    {
        $goods = new Goods();
        $goods->restaurant_id = $input['restaurant_id'];
        $goods->shop_id = $input['shop_id'];
        $goods->dinning_time_id = $input['dinning_time_id'];
        $goods->name = $input['name'];
        $goods->price = $input['price'];
        $goods->image = isset($input['image']) ? $input['image'] : '';
        $goods->is_temp = isset($input['is_temp']) ? $input['is_temp'] : 0;

        return $goods;
    }
}
