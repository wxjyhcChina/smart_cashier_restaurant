<?php

namespace App\Modules\Repositories\Shop;

use App\Modules\Models\Shop\Shop;
use App\Modules\Repositories\BaseRepository;

/**
 * Class BaseShopRepository.
 */
class BaseShopRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Shop::class;


    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }

}
