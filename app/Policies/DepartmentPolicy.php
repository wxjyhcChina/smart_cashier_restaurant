<?php

namespace App\Policies;

use App\Access\Model\User\User;
use App\Modules\Models\Department\Department;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the department.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  Department  $department
     * @return mixed
     */
    public function view(User $user, Department $department)
    {
        //
        return $user->restaurant_id == $department->restaurant_id;
    }

    /**
     * Determine whether the user can create departments.
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
     * @param Department $department
     * @return bool
     */
    public function edit(User $user, Department $department)
    {
        //
        return $user->restaurant_id == $department->restaurant_id;
    }

    /**
     * Determine whether the user can update the department.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  Department  $department
     * @return mixed
     */
    public function update(User $user, Department $department)
    {
        //
        return $user->restaurant_id == $department->restaurant_id;
    }

    /**
     * Determine whether the user can delete the department.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  Department  $department
     * @return mixed
     */
    public function delete(User $user, Department $department)
    {
        //
        return $user->restaurant_id == $department->restaurant_id;
    }
}
