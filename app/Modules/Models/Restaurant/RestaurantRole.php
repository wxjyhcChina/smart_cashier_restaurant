<?php

namespace App\Modules\Models\Agent;

use Illuminate\Database\Eloquent\Model;

class RestaurantRole extends Model
{
    protected $fillable = ['restaurant_id', 'name', 'all', 'sort'];
}