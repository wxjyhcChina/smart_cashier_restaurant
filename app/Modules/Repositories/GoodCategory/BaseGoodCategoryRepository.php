<?php

namespace App\Modules\Repositories\GoodCategory;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Department\Department;
use App\Modules\Models\GoodCategory\GoodCategory;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Label\Label;
use App\Modules\Models\Shop\Shop;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseGoodsRepository.
 */
class BaseGoodCategoryRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = GoodCategory::class;


    private function goodCategoryExist($name, $updateGoodCategory = null,$shop_id)
    {
        $goodCategoryQuery = GoodCategory::query();

        if ($updateGoodCategory != null)
        {
            $departmentQuery = $goodCategoryQuery->where('id', '<>', $updateGoodCategory->id);
        }

        $goodCategoryQuery = $goodCategoryQuery->where(function ($query) use ($name,$shop_id){
            $query->where('name', $name)->where('shop_id',$shop_id);
        });

        if ($goodCategoryQuery->first() != null)
        {
            throw new ApiException(ErrorCode::DEPARTMENT_ALREADY_EXIST, trans('exceptions.backend.goodCategory.already_exist'));
        }
    }
    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()
            ->where('restaurant_id', $restaurant_id);
    }

    public function getByShopQuery($shop_id)
    {
        return $this->query()
            ->where('shop_id', $shop_id);
    }
    

    /**
     * @param $input
     * @return Goods
     * @throws ApiException
     */
    public function create($input)
    {
        //去重
        $this->goodCategoryExist($input['name'],null,$input['shop_id']);

        $goodCategory = $this->createGoodCategoryStub($input);
        try
        {
            DB::beginTransaction();

            $goodCategory->save();

            DB::commit();
            return $goodCategory;
        }catch (\Exception $exception){
            DB::rollBack();

            if ($exception instanceof ApiException)
            {
                throw $exception;
            }
            else
            {
                throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.goodCategory.create_error'));
            }
        }

    }

    /**
     * @param GoodCategory $goodCategory
     * @param $input
     * @return Goods
     * @throws ApiException
     */
    public function update(GoodCategory $goodCategory, $input)
    {
        $this->goodCategoryExist($input['name'],$goodCategory,$input['shop_id']);
        Log::info("goodCategory update param:".json_encode($input));

        try
        {
            DB::beginTransaction();
            $goodCategory->update($input);

            DB::commit();

            return;
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
                throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.goodCategory.update_error'));
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
    private function createGoodCategoryStub($input)
    {
        $goodCategory = new GoodCategory();
        $goodCategory->restaurant_id = $input['restaurant_id'];
        $goodCategory->shop_id = $input['shop_id'];
        $goodCategory->name = $input['name'];
        Log::info("goodCategory store param:".json_encode($input));

        return $goodCategory;
    }
}
