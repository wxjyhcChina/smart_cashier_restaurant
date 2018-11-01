<?php

namespace App\Modules\Models\Restaurant;

use App\Modules\Models\Restaurant\Traits\Attribute\RestaurantUserAttribute;
use App\Modules\Models\Restaurant\Traits\Relationship\RestaurantUserRelationship;
use Illuminate\Database\Eloquent\Model;

class RestaurantUser extends Model
{
    use RestaurantUserAttribute, RestaurantUserRelationship;

    protected $fillable = ['first_name', 'last_name', 'password', 'status'];
}