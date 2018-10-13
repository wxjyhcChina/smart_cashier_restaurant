<?php

namespace App\Modules\Models\Card\Traits\Relationship;
use App\Modules\Models\Customer\Customer;

/**
 * Class CardRelationship
 * @package App\Modules\Models\Card\Traits\Relationship
 */
trait CardRelationship
{
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}