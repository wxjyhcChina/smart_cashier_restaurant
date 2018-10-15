<?php

namespace App\Modules\Models\Label\Traits\Relationship;

use App\Modules\Models\Label\Label;

/**
 * Class LabelCategoryRelationship
 * @package App\Modules\Models\Label\Traits\Relationship
 */
trait LabelCategoryRelationship
{
    /**
     * @return mixed
     */
    public function labels()
    {
        return $this->hasMany(Label::class);
    }
}