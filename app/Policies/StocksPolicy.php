<?php

namespace App\Policies;

use App\Access\Model\User\User;
use App\Modules\Models\Stocks\Stocks;
use Illuminate\Auth\Access\HandlesAuthorization;

class StocksPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  Stocks  $stocks
     * @return mixed
     */
    public function view(User $user, Stocks $stocks)
    {
        //
        return $user->restaurant_id == $stocks->restaurant_id;
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
     * @param Stocks $stocks
     * @return bool
     */
    public function edit(User $user, Stocks $stocks)
    {
        //
        return $user->restaurant_id == $stocks->restaurant_id;
    }

    /**
     * Determine whether the user can update the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  Stocks $stocks
     * @return mixed
     */
    public function update(User $user, Stocks $stocks)
    {
        //
        return $user->restaurant_id == $stocks->restaurant_id;
    }

    /**
     * Determine whether the user can delete the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  Stocks $stocks
     * @return mixed
     */
    public function delete(User $user, Stocks $stocks)
    {
        //
        return $user->restaurant_id == $stocks->restaurant_id;
    }
}
