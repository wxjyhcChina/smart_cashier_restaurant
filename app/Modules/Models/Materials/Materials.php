<?php

namespace App\Modules\Models\Materials;

use App\Modules\Models\Materials\Traits\Attribute\MaterialsAttribute;
use App\Modules\Models\Materials\Traits\Relationship\MaterialsRelationship;
use Illuminate\Database\Eloquent\Model;

class Materials extends Model
{
    use MaterialsAttribute, MaterialsRelationship;

    protected $table = 'materials';

    protected $fillable = ['id','name', 'restaurant_id', 'shop_id','main_supplier'];
}