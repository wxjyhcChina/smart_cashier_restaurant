<?php

namespace App\Modules\Models\Label\Traits\Relationship;

use App\Modules\Models\Goods\Goods;
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

    /**
     * @return mixed
     */
    public function goods()
    {
        return $this->belongsToMany(Goods::class, 'label_category_goods', 'label_category_id', 'goods_id');
    }
}