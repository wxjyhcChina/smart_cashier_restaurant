<?php

namespace App\Modules\Models\Customer\Traits\Relationship;

use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Modules\Models\RechargeOrder\RechargeOrder;

/**
 * Class AccountRecordRelationship
 * @package App\Modules\Models\Customer\Traits\Relationship
 */
trait AccountRecordRelationship
{
    /**
     * @return mixed
     */
    public function recharge_order()
    {
        return $this->belongsTo(RechargeOrder::class);
    }

    public function consume_order()
    {
        return $this->belongsTo(ConsumeOrder::class);
    }
}