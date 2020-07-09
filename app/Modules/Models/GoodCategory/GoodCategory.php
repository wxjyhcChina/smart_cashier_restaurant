<?php

namespace App\Modules\Models\GoodCategory;

use App\Modules\Models\GoodCategory\Traits\Attribute\GoodCategoryAttribute;
use App\Modules\Models\GoodCategory\Traits\Relationship\GoodCategoryRelationship;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class GoodCategory extends Model
{
    use GoodCategoryAttribute, GoodCategoryRelationship, SoftDeletes;

    protected $fillable = ['id', 'restaurant_id', 'shop_id', 'name'];

    protected $table = 'good_categories';


}