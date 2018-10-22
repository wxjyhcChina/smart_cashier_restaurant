<?php

namespace App\Modules\Models\Shop;

use App\Modules\Models\Shop\Traits\Attribute\ShopAttribute;
use App\Modules\Models\Shop\Traits\Relationship\ShopRelationship;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use ShopAttribute, ShopRelationship;

    protected $fillable = ['name', 'default', 'enabled'];
}