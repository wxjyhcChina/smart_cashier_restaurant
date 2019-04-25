<?php

namespace App\Modules\Repositories\ConsumeOrder;

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
        return $this->query()
            ->where('consume_orders.restaurant_id', $restaurant_id)
            ->with('goods')
            ->with('customer')
            ->with('card')
            ->with('dinning_time');
    }
}
