<?php

namespace App\Modules\Models\Goods\Traits\Relationship;
use App\Modules\Models\DinningTime\DinningTime;
use App\Modules\Models\Label\LabelCategory;
use App\Modules\Models\Shop\Shop;

/**
 * Class GoodsRelationship
 * @package App\Modules\Models\Goods\Traits\Relationship
 */
trait GoodsRelationship
{
    /**
     * @return mixed
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * @return mixed
     */
    public function dinning_time()
    {
        return $this->belongsTo(DinningTime::class);
    }

    /**
     * @return mixed
     */
    public function labelCategories()
    {
        return $this->hasMany(LabelCategory::class);
    }
}