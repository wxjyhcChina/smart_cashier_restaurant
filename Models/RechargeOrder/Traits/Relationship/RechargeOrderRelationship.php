<?php

namespace App\Modules\Models\RechargeOrder\Traits\Relationship;

use App\Modules\Models\Customer\Customer;

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
}