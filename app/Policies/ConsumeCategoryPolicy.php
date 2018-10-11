<?php

namespace App\Policies;

use App\Access\Model\User\User;
use App\Modules\Models\ConsumeCategory\ConsumeCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConsumeCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the consumeCategory.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  ConsumeCategory  $consumeCategory
     * @return mixed
     */
    public function view(User $user, ConsumeCategory $consumeCategory)
    {
        //
        return $user->restaurant_id == $consumeCategory->restaurant_id;
    }

    /**
     * Determine whether the user can create consumeCategories.
     *
     * @param  \App\Access\Model\User\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * @param User $user
     * @param ConsumeCategory $consumeCategory
     * @return bool
     */
    public function edit(User $user, ConsumeCategory $consumeCategory)
    {
        //
        return $user->restaurant_id == $consumeCategory->restaurant_id;
    }


    /**
     * Determine whether the user can update the consumeCategory.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  ConsumeCategory  $consumeCategory
     * @return mixed
     */
    public function update(User $user, ConsumeCategory $consumeCategory)
    {
        //
        return $user->restaurant_id == $consumeCategory->restaurant_id;
    }

    /**
     * Determine whether the user can delete the consumeCategory.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  ConsumeCategory  $consumeCategory
     * @return mixed
     */
    public function delete(User $user, ConsumeCategory $consumeCategory)
    {
        //
        return $user->restaurant_id == $consumeCategory->restaurant_id;
    }
}
