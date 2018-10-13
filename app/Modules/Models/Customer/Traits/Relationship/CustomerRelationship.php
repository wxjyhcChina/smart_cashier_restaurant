<?php

namespace App\Modules\Models\Customer\Traits\Relationship;

use App\Modules\Enums\CardStatus;
use App\Modules\Models\Card\Card;
use App\Modules\Models\ConsumeCategory\ConsumeCategory;
use App\Modules\Models\Department\Department;

/**
 * Class CustomerRelationship
 * @package App\Modules\Models\Customer\Traits\Relationship
 */
trait CustomerRelationship
{
    public function card()
    {
        return $this->hasOne(Card::class)->where('status', CardStatus::ACTIVATED);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function consume_category()
    {
        return $this->belongsTo(ConsumeCategory::class);
    }
}