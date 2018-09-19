<?php

namespace App\Modules\Repositories\DinningTime;

use App\Modules\Models\DinningTime\DinningTime;
use App\Modules\Repositories\BaseRepository;

/**
 * Class BaseDinningTimeRepository.
 */
class BaseDinningTimeRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = DinningTime::class;


    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }

}
