<?php

namespace App\Modules\Repositories\Label;

use App\Modules\Models\Label\Label;
use App\Modules\Repositories\BaseRepository;

/**
 * Class BaseGoodsRepository.
 */
class BaseLabelRepository extends BaseRepository
{
    /**
     * Associated Repository Model.
     */
    const MODEL = Label::class;

    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }
}
