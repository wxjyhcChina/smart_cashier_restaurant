<?php

namespace App\Modules\Repositories\Restaurant;

use App\Modules\Models\Restaurant\Restaurant;
use App\Modules\Repositories\BaseRepository;

/**
 * Class BaseRestaurantRepository.
 */
class BaseRestaurantRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Restaurant::class;

}
