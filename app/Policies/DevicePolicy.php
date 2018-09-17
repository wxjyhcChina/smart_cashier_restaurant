<?php

namespace App\Policies;

use App\Access\Model\User\User;
use App\Modules\Models\Device\Device;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the device.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  \App\Modules\Models\Device\Device  $device
     * @return mixed
     */
    public function view(User $user, Device $device)
    {
        //
        return $user->restaurant_id == $device->restaurant_id;
    }

    /**
     * Determine whether the user can create devices.
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
     * @param Device $device
     * @return bool
     */
    public function edit(User $user, Device $device)
    {
        //
        return $user->restaurant_id == $device->restaurant_id;
    }

    /**
     * Determine whether the user can update the device.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  \App\Modules\Models\Device\Device  $device
     * @return mixed
     */
    public function update(User $user, Device $device)
    {
        //
        return $user->restaurant_id == $device->restaurant_id;
    }

    /**
     * Determine whether the user can delete the device.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  \App\Modules\Models\Device\Device  $device
     * @return mixed
     */
    public function delete(User $user, Device $device)
    {
        //
        return $user->restaurant_id == $device->restaurant_id;
    }
}
