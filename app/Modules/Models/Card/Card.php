<?php

namespace App\Modules\Models\Card;

use App\Modules\Models\Card\Traits\Attribute\CardAttribute;
use App\Modules\Models\Card\Traits\Relationship\CardRelationship;
use Illuminate\Database\Eloquent\Model;


class Card extends Model
{
    use CardAttribute, CardRelationship;

    protected $fillable = ['id', 'restaurant_id', 'customer_id', 'number', 'internal_number', 'customer_id', 'status', 'enabled'];
}