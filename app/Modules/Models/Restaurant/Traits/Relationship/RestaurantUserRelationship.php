<?php

namespace App\Modules\Models\Restaurant\Traits\Relationship;

use App\Modules\Models\Restaurant\RestaurantRole;
use App\Modules\Models\Restaurant\Restaurant;

/**
 * Class RestaurantUserRelationship
 * @package App\Modules\Models\Restaurant\Traits\Relationship
 */
trait RestaurantUserRelationship
{
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    
    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(RestaurantRole::class, 'restaurant_role_user', 'user_id', 'role_id');
    }
}