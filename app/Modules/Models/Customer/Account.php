<?php

namespace App\Modules\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['id', 'customer_id', 'balance', 'subsidy_balance'];
}