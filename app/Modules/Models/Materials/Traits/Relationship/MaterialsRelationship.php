<?php

namespace App\Modules\Models\Materials\Traits\Relationship;

use App\Modules\Models\Goods\Goods;
use App\Modules\Models\Materials\Materials;
use App\Modules\Models\Restaurant\Restaurant;
use App\Modules\Models\Shop\Shop;


/**
 * Class MaterialsRelationship
 * @package App\Modules\Models\Materials\Traits\Relationship
 */
trait MaterialsRelationship
{
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function goods()
    {
        return $this->belongsToMany(Goods::class, 'material_goods', 'material_id', 'goods_id');
    }

}