<?php

namespace App\Modules\Models\Customer;

use App\Modules\Enums\AccountRecordType;
use Illuminate\Database\Eloquent\Model;

class AccountRecord extends Model
{
    protected $fillable = ['id', 'customer_id','account_id', 'card_id', 'type', 'recharge_order_id', 'consume_order_id', 'money', 'pay_method'];

    public function getMoneyAttribute($value)
    {
        if ($this->type == AccountRecordType::CONSUME || AccountRecordType::SYSTEM_MINUS)
        {
            $value = -$value;
        }

        return $value.'元';
    }
}