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
        return $this->belongsToMany(DinningTime::class, 'goods_dinning_time', 'goods_id', 'dinning_time_id');
    }

    /**
     * @return mixed
     */
    public function label_categories()
    {
        return $this->belongsToMany(LabelCategory::class, 'label_category_goods', 'goods_id', 'label_category_id');
    }
}