<?php

namespace App\Policies;

use App\Access\Model\User\User;
use App\Modules\Models\Shop\Shop;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  Shop  $shop
     * @return mixed
     */
    public function view(User $user, Shop $shop)
    {
        //
        return $user->restaurant_id == $shop->restaurant_id;
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
     * @param Shop $shop
     * @return bool
     */
    public function edit(User $user, Shop $shop)
    {
        //
        return $user->restaurant_id == $shop->restaurant_id;
    }

    /**
     * Determine whether the user can update the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  Shop  $shop
     * @return mixed
     */
    public function update(User $user, Shop $shop)
    {
        //
        return $user->restaurant_id == $shop->restaurant_id;
    }

    /**
     * Determine whether the user can delete the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  Shop  $shop
     * @return mixed
     */
    public function delete(User $user, Shop $shop)
    {
        //
        return $user->restaurant_id == $shop->restaurant_id;
    }
}
