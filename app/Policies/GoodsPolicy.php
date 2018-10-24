<?php

namespace App\Policies;

use App\Access\Model\User\User;
use App\Modules\Models\Goods\Goods;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoodsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  Goods $goods
     * @return mixed
     */
    public function view(User $user, Goods $goods)
    {
        //
        return $user->restaurant_id == $goods->restaurant_id;
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
    public function edit(User $user, Goods $goods)
    {
        //
        return $user->restaurant_id == $goods->restaurant_id;
    }

    /**
     * Determine whether the user can update the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  Goods $goods
     * @return mixed
     */
    public function update(User $user, Goods $goods)
    {
        //
        return $user->restaurant_id == $goods->restaurant_id;
    }

    /**
     * Determine whether the user can delete the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  Goods $goods
     * @return mixed
     */
    public function delete(User $user, Goods $goods)
    {
        //
        return $user->restaurant_id == $goods->restaurant_id;
    }
}
