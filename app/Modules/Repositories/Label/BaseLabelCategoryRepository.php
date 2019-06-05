<?php

namespace App\Modules\Repositories\Label;

use App\Exceptions\Api\ApiException;
use App\Modules\Enums\ErrorCode;
use App\Modules\Models\Label\Label;
use App\Modules\Models\Label\LabelCategory;
use App\Modules\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class BaseGoodsRepository.
 */
class BaseLabelCategoryRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = LabelCategory::class;

    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }

    /**
     * @param $labelCategory
     * @return mixed
     */
    public function getLabels($labelCategory)
    {
        return $labelCategory->labels;
    }

    public function labelCategoryExist($name, $updatedLabelsCategory = null)
    {
        $labelCategoryQuery = LabelCategory::where('name', $name);

        if ($updatedLabelsCategory != null)
        {
            $labelCategoryQuery->where('id', '<>', $updatedLabelsCategory->id);
        }

        if ($labelCategoryQuery->first() != null)
        {
            throw new ApiException(ErrorCode::LABEL_CATEGORY_ALREADY_EXIST, trans('exceptions.backend.labelCategory.already_exist'));
        }
    }

    /**
     * @param $input
     * @return LabelCategory
     * @throws ApiException
     */
    public function create($input)
    {
        $this->labelCategoryExist($input['name']);
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
        $this->labelCategoryExist($input['name'], $labelCategory);
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
