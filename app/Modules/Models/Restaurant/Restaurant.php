<?php

namespace App\Modules\Models\Restaurant;

use App\Modules\Models\Restaurant\Traits\Attribute\RestaurantAttribute;
use App\Modules\Models\Restaurant\Traits\Relationship\RestaurantRelationship;
use Illuminate\Database\Eloquent\Model;


class Restaurant extends Model
{
    use RestaurantAttribute, RestaurantRelationship;

    protected $fillable = ['uuid', 'name', 'address', 'ad_code', 'city_name', 'lat', 'lng', 'contact', 'telephone', 'enabled'];
}