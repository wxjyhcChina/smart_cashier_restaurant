<?php

namespace App\Policies;

use App\Access\Model\User\User;
use App\Modules\Models\GoodCategory\GoodCategory;
use App\Modules\Models\Goods\Goods;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoodCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  GoodCategory $goodCategory
     * @return mixed
     */
    public function view(User $user, GoodCategory $goodCategory)
    {
        //
        return $user->restaurant_id == $goodCategory->restaurant_id;
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
     * @param Goods $goods
     * @return bool
     */
    public function edit(User $user, GoodCategory $goodCategory)
    {
        //
        return $user->restaurant_id == $goodCategory->restaurant_id;
    }

    /**
     * Determine whether the user can update the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  GoodCategory $goodCategory
     * @return mixed
     */
    public function update(User $user, GoodCategory $goodCategory)
    {
        //
        return $user->restaurant_id == $goodCategory->restaurant_id;
    }

    /**
     * Determine whether the user can delete the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  GoodCategory $goodCategory
     * @return mixed
     */
    public function delete(User $user, GoodCategory $goodCategory)
    {
        //
        return $user->restaurant_id == $goodCategory->restaurant_id;
    }
}
