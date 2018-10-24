<?php
/**
 * Created by PhpStorm.
 * User: billy
 * Date: 2018/9/13
 * Time: 16:33
 */

namespace App\Repositories\Backend\Label;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Label\LabelCategory;
use App\Modules\Repositories\Label\BaseLabelCategoryRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class LabelCategoryRepository
 * @package App\Repositories\Backend\Label
 */
class LabelCategoryRepository extends BaseLabelCategoryRepository
{

    /**
     * @param $input
     * @return LabelCategory
     * @throws ApiException
     */
    public function create($input)
    {
        $labelCategory = $this->createLabelCategoryStub($input);

        if ($labelCategory->save())
        {
            return $labelCategory;
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.labelCategory.create_error'));
    }

    /**
     * @param LabelCategory $labelCategory
     * @param $input
     * @throws ApiException
     */
    public function update(LabelCategory $labelCategory, $input)
    {
        Log::info("restaurant update param:".json_encode($input));

        try
        {
            DB::beginTransaction();
            $labelCategory->update($input);

            DB::commit();
            return;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();
        }

        throw new ApiException(ErrorCode::DATABASE_ERROR, trans('exceptions.backend.labelCategory.update_error'));
    }

    /**
     * @param $input
     * @return LabelCategory
     */
    private function createLabelCategoryStub($input)
    {
        $labelCategory = new LabelCategory();
        $labelCategory->image = isset($input['image']) ? $input['image'] : '';
        $labelCategory->name = $input['name'];
        $labelCategory->restaurant_id = $input['restaurant_id'];

        return $labelCategory;
    }

}