<?php

namespace App\Access\Repository\User;

use App\Access\Model\User\User;
use App\Exceptions\GeneralException;
use App\Access\Model\User\SocialLogin;
use App\Events\Backend\Access\User\UserSocialDeleted;

/**
 * Class UserSocialRepository.
 */
class UserSocialRepository
{
    /**
     * @param User        $user
     * @param SocialLogin $social
     *
     * @return bool
     * @throws GeneralException
     */
    public function delete(User $user, SocialLogin $social)
    {
        if ($user->providers()->whereId($social->id)->delete()) {
            event(new UserSocialDeleted($user, $social));

            return true;
        }

        throw new GeneralException(trans('exceptions.backend.access.users.social_delete_error'));
    }
}
