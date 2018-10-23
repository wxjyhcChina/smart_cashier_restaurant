<?php

namespace App\Modules\Models\Customer;

use App\Modules\Models\Customer\Traits\Attribute\CustomerAttribute;
use App\Modules\Models\Customer\Traits\Relationship\CustomerRelationship;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use CustomerAttribute, CustomerRelationship;

    protected $fillable = ['restaurant_id', 'user_name', 'telephone', 'id_license', 'birthday', 'department_id', 'consume_category_id', 'enabled'];

    protected $appends = ['balance'];

    protected $hidden = ['account'];

    /**
     * @return int
     */
    public function getBalanceAttribute()
    {
        return $this->account->balance;
    }
}