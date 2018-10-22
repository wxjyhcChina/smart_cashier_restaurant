<?php

namespace App\Modules\Repositories\Label;

use App\Modules\Models\Label\LabelCategory;
use App\Modules\Repositories\BaseRepository;

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
}
