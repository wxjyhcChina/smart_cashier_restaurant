<?php

namespace App\Modules\Repositories\PayMethod;

use App\Modules\Models\PayMethod\PayMethod;
use App\Modules\Repositories\BaseRepository;

/**
 * Class BasePayMethodRepository.
 */
class BasePayMethodRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = PayMethod::class;

    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }
}
