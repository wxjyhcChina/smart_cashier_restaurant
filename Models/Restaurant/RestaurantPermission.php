<?php

namespace App\Modules\Models\Restaurant;

use Illuminate\Database\Eloquent\Model;

class RestaurantPermission extends Model
{
    protected $fillable = ['name', 'display_name', 'sort'];
}