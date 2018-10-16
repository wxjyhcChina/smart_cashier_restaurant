<?php

namespace App\Modules\Repositories\ConsumeOrde;

use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Modules\Repositories\BaseRepository;

/**
 * Class BaseConsumeOrderRepository.
 */
class BaseConsumeOrderRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = ConsumeOrder::class;


    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }
}
