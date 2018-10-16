<?php

namespace App\Modules\Models\PayMethod;

use App\Modules\Models\PayMethod\Traits\Attribute\PayMethodAttribute;
use App\Modules\Models\PayMethod\Traits\Relationship\PayMethodRelationship;
use Illuminate\Database\Eloquent\Model;

class PayMethod extends Model
{
    use PayMethodAttribute, PayMethodRelationship;

    protected $fillable = ['id', 'restaurant_id', 'method', 'enabled'];
}