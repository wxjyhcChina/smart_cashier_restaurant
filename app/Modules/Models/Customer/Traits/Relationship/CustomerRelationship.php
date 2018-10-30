<?php

namespace App\Modules\Models\Customer\Traits\Relationship;

use App\Modules\Enums\CardStatus;
use App\Modules\Models\Card\Card;
use App\Modules\Models\ConsumeCategory\ConsumeCategory;
use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Modules\Models\Customer\Account;
use App\Modules\Models\Customer\AccountRecord;
use App\Modules\Models\Department\Department;

/**
 * Class CustomerRelationship
 * @package App\Modules\Models\Customer\Traits\Relationship
 */
trait CustomerRelationship
{
    /**
     * @return mixed
     */
    public function card()
    {
        return $this->hasOne(Card::class)->where('status', CardStatus::ACTIVATED);
    }

    /**
     * @return mixed
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return mixed
     */
    public function consume_category()
    {
        return $this->belongsTo(ConsumeCategory::class);
    }

    /**
     * @return mixed
     */
    public function account()
    {
        return $this->hasOne(Account::class);
    }

    /**
     * @return mixed
     */
    public function account_records()
    {
        return $this->hasMany(AccountRecord::class);
    }

    /**
     * @return mixed
     */
    public function consume_orders()
    {
        return $this->hasMany(ConsumeOrder::class);
    }
}