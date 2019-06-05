<?php

namespace App\Modules\Repositories\ConsumeCategory;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\ConsumeCategory\ConsumeCategory;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseConsumeCategoryRepository.
 */
class BaseConsumeCategoryRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = ConsumeCategory::class;


    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }

    public function consumeCategoryExist($name, $updatedConsumeCategory = null)
    {
        $consumeCategoryQuery = ConsumeCategory::where('name', $name);

        if ($updatedConsumeCategory != null)
        {
            $consumeCategoryQuery->where('id', '<>', $updatedConsumeCategory->id);
        }

        if ($consumeCategoryQuery->first() != null)
        {
            throw new ApiException(ErrorCode::CONSUME_CATEGORY_ALREADY_EXIST, trans('exceptions.backend.consumeCategory.already_exist'));
        }
    }

    /**
     * @param $input
     * @return ConsumeCategory
     * @throws ApiException
     */
    public function create($input)
    {
        $this->consumeCategoryExist($input['name']);
        $consumeCategory = $this->createConsumeCategoryStub($input);

        if ($consumeCategory->save())
        {
            return $consumeCategory;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.consumeCategory.create_error'));
    }

    /**
     * @param ConsumeCategory $consumeCategory
     * @param $input
     * @throws ApiException
     */
    public function update(ConsumeCategory $consumeCategory, $input)
    {
        $this->consumeCategoryExist($input['name'], $consumeCategory);
        Log::info("consume category update param:".json_encode($input));

        try
        {
            DB::beginTransaction();
            $consumeCategory->update($input);

            DB::commit();
            return;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.consumeCategory.update_error'));
    }

    /**
     * @param $input
     * @return ConsumeCategory
     */
    private function createConsumeCategoryStub($input)
    {
        $consumeCategory = new ConsumeCategory();
        $consumeCategory->restaurant_id = $input['restaurant_id'];
        $consumeCategory->name = $input['name'];
        $consumeCategory->recharge_rate = 1;

        return $consumeCategory;
    }
}
