<?php

namespace App\Policies;

use App\Access\Model\User\User;
use App\Modules\Models\Card\Card;
use Illuminate\Auth\Access\HandlesAuthorization;

class CardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the card.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  \App\Modules\Models\Card\Card  $card
     * @return mixed
     */
    public function view(User $user, Card $card)
    {
        //
        return $user->restaurant_id == $card->restaurant_id;
    }

    /**
     * Determine whether the user can create cards.
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
     * @param Card $card
     * @return bool
     */
    public function edit(User $user, Card $card)
    {
        //
        return $user->restaurant_id == $card->restaurant_id;
    }

    /**
     * Determine whether the user can update the card.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  \App\Modules\Models\Card\Card  $card
     * @return mixed
     */
    public function update(User $user, Card $card)
    {
        //
        return $user->restaurant_id == $card->restaurant_id;
    }

    /**
     * Determine whether the user can delete the card.
     *
     * @param  \App\Access\Model\User\User  $user
     * @param  \App\Modules\Models\Card\Card  $card
     * @return mixed
     */
    public function delete(User $user, Card $card)
    {
        //
        return $user->restaurant_id == $card->restaurant_id;
    }
}
