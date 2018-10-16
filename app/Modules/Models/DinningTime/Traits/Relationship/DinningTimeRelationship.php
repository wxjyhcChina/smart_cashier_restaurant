<?php

namespace App\Modules\Models\DinningTime\Traits\Relationship;

use App\Modules\Models\ConsumeRule\ConsumeRule;

/**
 * Class DinningTimeRelationship
 * @package App\Modules\Models\DinningTime\Traits\Relationship
 */
trait DinningTimeRelationship
{
    /**
     * @return mixed
     */
    public function consume_rules()
    {
        return $this->belongsToMany(ConsumeRule::class, 'consume_rule_dinning_time', 'dinning_time_id', 'consume_rule_id');
    }
}