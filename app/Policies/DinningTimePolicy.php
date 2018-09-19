<?php

namespace App\Policies;

use App\Access\Model\User\User;
use App\Modules\Models\DinningTime\DinningTime;
use Illuminate\Auth\Access\HandlesAuthorization;

class DinningTimePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the dinningTime.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  DinningTime  $dinningTime
     * @return mixed
     */
    public function view(User $user, DinningTime $dinningTime)
    {
        //
        return $user->restaurant_id == $dinningTime->restaurant_id;
    }

    /**
     * Determine whether the user can create dinningTimes.
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
     * @param DinningTime $dinningTime
     * @return bool
     */
    public function edit(User $user, DinningTime $dinningTime)
    {
        //
        return $user->restaurant_id == $dinningTime->restaurant_id;
    }

    /**
     * Determine whether the user can update the dinningTime.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  DinningTime  $dinningTime
     * @return mixed
     */
    public function update(User $user, DinningTime $dinningTime)
    {
        //
        return $user->restaurant_id == $dinningTime->restaurant_id;
    }

    /**
     * Determine whether the user can delete the dinningTime.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  DinningTime  $dinningTime
     * @return mixed
     */
    public function delete(User $user, DinningTime $dinningTime)
    {
        //
        return $user->restaurant_id == $dinningTime->restaurant_id;
    }
}
