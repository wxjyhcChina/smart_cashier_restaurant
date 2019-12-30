<?php

namespace App\Modules\Models\Restaurant;

use App\Modules\Models\Restaurant\Traits\Attribute\RestaurantAttribute;
use App\Modules\Models\Restaurant\Traits\Relationship\RestaurantRelationship;
use Illuminate\Database\Eloquent\Model;


class Restaurant extends Model
{
    use RestaurantAttribute, RestaurantRelationship;

    protected $fillable = ['uuid', 'name', 'address', 'ad_code', 'city_name', 'lat', 'lng', 'contact', 'telephone', 'logo', 'enabled'];

    public function getLogoAttribute($value)
    {
        if ($value == null)
        {
            return '';
        }

        if ($value == '' || strpos($value, 'http') === 0)
        {
            return $value;
        }

        return config('constants.qiniu.image_bucket_url').$value;
    }
}