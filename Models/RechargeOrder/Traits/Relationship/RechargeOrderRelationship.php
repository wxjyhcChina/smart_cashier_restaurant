<?php

namespace App\Modules\Models\RechargeOrder\Traits\Relationship;

use App\Modules\Models\Card\Card;
use App\Modules\Models\Customer\Customer;
use App\Modules\Models\Restaurant\RestaurantUser;

/**
 * Class RechargeOrderRelationship
 * @package App\Modules\Models\RechargeOrder\Traits\Relationship
 */
trait RechargeOrderRelationship
{
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function restaurant_user()
    {
        return $this->belongsTo(RestaurantUser::class);
    }
}