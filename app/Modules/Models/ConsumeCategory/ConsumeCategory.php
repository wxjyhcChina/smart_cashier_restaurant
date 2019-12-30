<?php

namespace App\Modules\Models\ConsumeCategory;

use App\Modules\Models\ConsumeCategory\Traits\Attribute\ConsumeCategoryAttribute;
use App\Modules\Models\ConsumeCategory\Traits\Relationship\ConsumeCategoryRelationship;
use Illuminate\Database\Eloquent\Model;

class ConsumeCategory extends Model
{
    use ConsumeCategoryAttribute, ConsumeCategoryRelationship;

    protected $fillable = ['restaurant_id', 'name'];
}