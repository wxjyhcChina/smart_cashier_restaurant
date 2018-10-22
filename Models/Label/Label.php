<?php

namespace App\Modules\Models\Label;

use App\Modules\Models\Label\Traits\Attribute\LabelAttribute;
use App\Modules\Models\Label\Traits\Relationship\LabelRelationship;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use LabelAttribute, LabelRelationship;

    protected $fillable = ['id', 'label_category_id', 'rfid'];
}