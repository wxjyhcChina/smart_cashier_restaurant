<?php

namespace App\Modules\Repositories\Device;

use App\Modules\Models\Device\Device;
use App\Modules\Repositories\BaseRepository;

/**
 * Class BaseDeviceRepository.
 */
class BaseDeviceRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Device::class;

    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }

}
