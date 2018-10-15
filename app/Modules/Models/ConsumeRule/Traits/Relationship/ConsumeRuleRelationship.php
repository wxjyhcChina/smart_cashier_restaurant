<?php

namespace App\Modules\Models\ConsumeRule\Traits\Relationship;

use App\Modules\Models\ConsumeCategory\ConsumeCategory;
use App\Modules\Models\DinningTime\DinningTime;


/**
 * Class ConsumeRuleRelationship
 * @package App\Modules\Models\ConsumeRule\Traits\Relationship
 */
trait ConsumeRuleRelationship
{
    /**
     * @return mixed
     */
    public function dinning_time()
    {
        return $this->belongsToMany(DinningTime::class, 'consume_rule_dinning_time', 'consume_rule_id', 'dinning_time_id');
    }


    /**
     * @return mixed
     */
    public function consume_categories()
    {
        return $this->belongsToMany(ConsumeCategory::class, 'consume_rule_consume_category', 'consume_rule_id', 'consume_category_id');
    }

}