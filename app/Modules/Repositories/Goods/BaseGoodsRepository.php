<?php

namespace App\Modules\Repositories\Goods;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Goods\Goods;
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
        return $this->query()->where('restaurant_id', $restaurant_id);
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
     * @param $input
     * @return Goods
     * @throws ApiException
     */
    public function create($input)
    {
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

        return $goods;
    }
}
