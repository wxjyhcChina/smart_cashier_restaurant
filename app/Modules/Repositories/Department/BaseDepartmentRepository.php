<?php

namespace App\Modules\Repositories\Department;

use App\Modules\Models\Department\Department;
use App\Modules\Repositories\BaseRepository;

/**
 * Class BaseDepartmentRepository.
 */
class BaseDepartmentRepository extends BaseRepository
{

    /**
     * Associated Repository Model.
     */
    const MODEL = Department::class;


    public function getByRestaurantQuery($restaurant_id)
    {
        return $this->query()->where('restaurant_id', $restaurant_id);
    }

}
