<?php

namespace App\Policies;

use App\Access\Model\User\User;
use App\Modules\Models\RechargeOrder\RechargeOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class RechargeOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the customer.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  RechargeOrder $rechargeOrder
     * @return mixed
     */
    public function view(User $user, RechargeOrder $rechargeOrder)
    {
        //
        return $user->restaurant_id == $rechargeOrder->restaurant_id;
    }

    /**
     * Determine whether the user can create customer.
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
     * @param RechargeOrder $rechargeOrder
     * @return bool
     */
    public function edit(User $user, RechargeOrder $rechargeOrder)
    {
        //
        return $user->restaurant_id == $rechargeOrder->restaurant_id;
    }

    /**
     * Determine whether the user can update the customer.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  RechargeOrder $rechargeOrder
     * @return mixed
     */
    public function update(User $user, RechargeOrder $rechargeOrder)
    {
        //
        return $user->restaurant_id == $rechargeOrder->restaurant_id;
    }

    /**
     * Determine whether the user can delete the customer.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  RechargeOrder $rechargeOrder
     * @return mixed
     */
    public function delete(User $user, RechargeOrder $rechargeOrder)
    {
        //
        return $user->restaurant_id == $rechargeOrder->restaurant_id;
    }
}
