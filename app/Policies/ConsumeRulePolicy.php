<?php

namespace App\Policies;

use App\Access\Model\User\User;
use App\Modules\Models\ConsumeRule\ConsumeRule;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConsumeRulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the consumeCategory.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  ConsumeRule $consumeRule
     * @return mixed
     */
    public function view(User $user, ConsumeRule $consumeRule)
    {
        //
        return $user->restaurant_id == $consumeRule->restaurant_id;
    }

    /**
     * Determine whether the user can create consumeCategories.
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
     * @param ConsumeRule $consumeRule
     * @return bool
     */
    public function edit(User $user, ConsumeRule $consumeRule)
    {
        //
        return $user->restaurant_id == $consumeRule->restaurant_id;
    }


    /**
     * Determine whether the user can update the consumeCategory.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  ConsumeRule $consumeRule
     * @return mixed
     */
    public function update(User $user, ConsumeRule $consumeRule)
    {
        //
        return $user->restaurant_id == $consumeRule->restaurant_id;
    }

    /**
     * Determine whether the user can delete the consumeCategory.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  ConsumeRule $consumeRule
     * @return mixed
     */
    public function delete(User $user, ConsumeRule $consumeRule)
    {
        //
        return $user->restaurant_id == $consumeRule->restaurant_id;
    }
}
