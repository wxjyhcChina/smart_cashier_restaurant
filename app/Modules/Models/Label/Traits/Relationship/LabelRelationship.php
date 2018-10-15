<?php

namespace App\Modules\Models\Label\Traits\Relationship;
use App\Modules\Models\DinningTime\DinningTime;
use App\Modules\Models\Label\LabelCategory;
use App\Modules\Models\Shop\Shop;

/**
 * Class LabelRelationship
 * @package App\Modules\Models\Label\Traits\Relationship
 */
trait LabelRelationship
{
    /**
     * @return mixed
     */
    public function labelCategory()
    {
        return $this->belongsTo(LabelCategory::class);
    }
}