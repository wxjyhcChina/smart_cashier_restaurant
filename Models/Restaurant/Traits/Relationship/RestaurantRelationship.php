<?php

namespace App\Modules\Models\Restaurant\Traits\Relationship;

use App\Modules\Models\Restaurant\RestaurantUser;

/**
 * Class RestaurantRelationship
 * @package App\Modules\Models\Restaurant\Traits\Relationship
 */
trait RestaurantRelationship
{
    /**
     * @return mixed
     */
    public function users()
    {
        return $this->hasMany(RestaurantUser::class);
    }
}