<?php

namespace App\Modules\Models\ConsumeOrder;

use App\Modules\Models\ConsumeOrder\Traits\Attribute\ConsumeOrderAttribute;
use App\Modules\Models\ConsumeOrder\Traits\Relationship\ConsumeOrderRelationship;
use Illuminate\Database\Eloquent\Model;

class ConsumeOrder extends Model
{
    use ConsumeOrderAttribute, ConsumeOrderRelationship;

    protected $fillable = ['customer_id', 'card_id', 'restaurant_id', 'restaurant_user_id', 'price', 'discount', 'pay_method', 'external_pay_no', 'status'];
}