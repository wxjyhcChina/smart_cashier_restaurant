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

    /**
     * @param $input
     * @return ConsumeCategory
     * @throws ApiException
     */
    public function create($input)
    {
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
