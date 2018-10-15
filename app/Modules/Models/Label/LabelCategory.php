<?php

namespace App\Modules\Models\Label;

use App\Modules\Models\Label\Traits\Relationship\LabelCategoryRelationship;
use Illuminate\Database\Eloquent\Model;

class LabelCategory extends Model
{
    use LabelCategoryRelationship;

    protected $fillable = ['name', 'image'];
}