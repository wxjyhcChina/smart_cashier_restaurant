<?php

namespace App\Policies;

use App\Access\Model\User\User;
use App\Modules\Models\PayMethod\PayMethod;
use Illuminate\Auth\Access\HandlesAuthorization;

class PayMethodPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  PayMethod $payMethod
     * @return mixed
     */
    public function view(User $user, PayMethod $payMethod)
    {
        //
        return $user->restaurant_id == $payMethod->restaurant_id;
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
     * @param PayMethod $payMethod
     * @return bool
     */
    public function edit(User $user, PayMethod $payMethod)
    {
        //
        return $user->restaurant_id == $payMethod->restaurant_id;
    }

    /**
     * Determine whether the user can update the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  PayMethod $payMethod
     * @return mixed
     */
    public function update(User $user, PayMethod $payMethod)
    {
        //
        return $user->restaurant_id == $payMethod->restaurant_id;
    }

    /**
     * Determine whether the user can delete the shop.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  PayMethod $payMethod
     * @return mixed
     */
    public function delete(User $user, PayMethod $payMethod)
    {
        //
        return $user->restaurant_id == $payMethod->restaurant_id;
    }
}
