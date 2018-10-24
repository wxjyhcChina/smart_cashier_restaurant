<?php

namespace App\Policies;

use App\Access\Model\User\User;
use App\Modules\Models\Label\LabelCategory;
use App\Modules\Models\Shop\Shop;
use Illuminate\Auth\Access\HandlesAuthorization;

class LabelCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  LabelCategory $labelCategory
     * @return mixed
     */
    public function view(User $user, LabelCategory $labelCategory)
    {
        //
        return $user->restaurant_id == $labelCategory->restaurant_id;
    }

    /**
     * Determine whether the user can create shops.
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
     * @param LabelCategory $labelCategory
     * @return bool
     */
    public function edit(User $user, LabelCategory $labelCategory)
    {
        //
        return $user->restaurant_id == $labelCategory->restaurant_id;
    }

    /**
     * Determine whether the user can update the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param LabelCategory $labelCategory
     * @return mixed
     */
    public function update(User $user, LabelCategory $labelCategory)
    {
        //
        return $user->restaurant_id == $labelCategory->restaurant_id;
    }

    /**
     * Determine whether the user can delete the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  LabelCategory $labelCategory
     * @return mixed
     */
    public function delete(User $user, LabelCategory $labelCategory)
    {
        //
        return $user->restaurant_id == $labelCategory->restaurant_id;
    }
}
