<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\ConsumeCategory;

use App\Exceptions\GeneralException;
use App\Modules\Models\ConsumeCategory\ConsumeCategory;
use App\Modules\Repositories\ConsumeCategory\BaseConsumeCategoryRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class ConsumeCategoryRepository
 * @package App\Repositories\Backend\ConsumeCategory
 */
class ConsumeCategoryRepository extends BaseConsumeCategoryRepository
{
    /**
     * @param $input
     * @return ConsumeCategory
     * @throws GeneralException
     */
    public function create($input)
    {
        $consumeCategory = $this->createConsumeCategoryStub($input);

        if ($consumeCategory->save())
        {
            return $consumeCategory;
        }

        throw new GeneralException(trans('exceptions.backend.consumeCategory.create_error'));
    }

    /**
     * @param ConsumeCategory $consumeCategory
     * @param $input
     * @throws GeneralException
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

        throw new GeneralException(trans('exceptions.backend.consumeCategory.update_error'));
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