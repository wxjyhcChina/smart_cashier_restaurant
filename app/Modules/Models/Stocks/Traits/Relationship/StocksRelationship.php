<?php

namespace App\Modules\Models\Stocks\Traits\Relationship;

use App\Modules\Models\Materials\Materials;
use App\Modules\Models\Restaurant\Restaurant;
use App\Modules\Models\Shop\Shop;


/**
 * Class StocksRelationship
 * @package App\Modules\Models\Stocks\Traits\Relationship
 */
trait StocksRelationship
{
    public function materials()
    {
        return $this->hasMany(Materials::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

}