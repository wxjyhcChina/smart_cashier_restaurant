<?php

namespace App\Modules\Models\Shop\Traits\Relationship;

use App\Modules\Models\Restaurant\Restaurant;

/**
 * Class ShopRelationship
 * @package App\Modules\Models\Shop\Traits\Relationship
 */
trait ShopRelationship
{
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}