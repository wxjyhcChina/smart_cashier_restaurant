<?php

namespace App\Modules\Models\ConsumeOrder;

use App\Modules\Models\ConsumeOrder\Traits\Attribute\ConsumeOrderAttribute;
use App\Modules\Models\ConsumeOrder\Traits\Relationship\ConsumeOrderRelationship;
use Illuminate\Database\Eloquent\Model;

class ConsumeOrder extends Model
{
    use ConsumeOrderAttribute, ConsumeOrderRelationship;

    protected $fillable = ['restaurant_id', 'name', 'discount', 'weekday', 'enabled'];
}