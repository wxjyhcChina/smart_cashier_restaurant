<?php

namespace App\Modules\Models\ConsumeOrder\Traits\Relationship;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Label\Label;


/**
 * Class ConsumeOrderRelationship
 * @package App\Modules\Models\ConsumeOrder\Traits\Relationship
 */
trait ConsumeOrderRelationship
{
    /**
     * @return mixed
     */
    public function goods()
    {
        return $this->belongsToMany(Goods::class, 'consume_order_goods', 'consume_order_id', 'goods_id');
    }

    /**
     * @return mixed
     */
    public function label()
    {
        return $this->belongsToMany(Label::class, 'consume_order_goods', 'consume_order_id', 'label_id');
    }
}