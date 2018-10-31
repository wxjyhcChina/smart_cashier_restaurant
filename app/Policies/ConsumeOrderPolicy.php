<?php

namespace App\Policies;

use App\Access\Model\User\User;
use App\Modules\Models\ConsumeOrder\ConsumeOrder;
use App\Modules\Models\Customer\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConsumeOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the customer.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  ConsumeOrder $consumeOrder
     * @return mixed
     */
    public function view(User $user, ConsumeOrder $consumeOrder)
    {
        //
        return $user->restaurant_id == $consumeOrder->restaurant_id;
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
     * @param ConsumeOrder $consumeOrder
     * @return bool
     */
    public function edit(User $user, ConsumeOrder $consumeOrder)
    {
        //
        return $user->restaurant_id == $consumeOrder->restaurant_id;
    }

    /**
     * Determine whether the user can update the customer.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  ConsumeOrder $consumeOrder
     * @return mixed
     */
    public function update(User $user, ConsumeOrder $consumeOrder)
    {
        //
        return $user->restaurant_id == $consumeOrder->restaurant_id;
    }

    /**
     * Determine whether the user can delete the customer.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  ConsumeOrder $consumeOrder
     * @return mixed
     */
    public function delete(User $user, ConsumeOrder $consumeOrder)
    {
        //
        return $user->restaurant_id == $consumeOrder->restaurant_id;
    }
}
