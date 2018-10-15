<?php

namespace App\Modules\Models\Label\Traits\Relationship;

use App\Modules\Models\Label\LabelCategory;

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