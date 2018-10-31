<?php

namespace App\Modules\Models\ConsumeOrder\Traits\Relationship;
use App\Modules\Models\Card\Card;
use App\Modules\Models\ConsumeCategory\ConsumeCategory;
use App\Modules\Models\Customer\Customer;
use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Label\Label;
use App\Modules\Models\PayMethod\PayMethod;


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
    public function labels()
    {
        return $this->belongsToMany(Label::class, 'consume_order_goods', 'consume_order_id', 'label_id');
    }

    /**
     * @return mixed
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return mixed
     */
    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * @return mixed
     */
    public function consume_category()
    {
        return $this->belongsTo(ConsumeCategory::class);
    }

}