<?php

namespace App\Modules\Models\ConsumeOrder;

use App\Modules\Models\RechargeOrder\Traits\Attribute\RechargeOrderAttribute;
use App\Modules\Models\RechargeOrder\Traits\Relationship\RechargeOrderRelationship;
use Illuminate\Database\Eloquent\Model;

class RechargeOrder extends Model
{
    use RechargeOrderAttribute, RechargeOrderRelationship;

    protected $fillable = ['customer_id', 'card_id', 'restaurant_id', 'restaurant_user_id', 'money', 'discount', 'pay_method', 'external_pay_no', 'status'];
}