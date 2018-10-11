<?php

namespace App\Modules\Repositories\ConsumeCategory;

use App\Modules\Models\ConsumeCategory\ConsumeCategory;
use App\Modules\Repositories\BaseRepository;

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

}
