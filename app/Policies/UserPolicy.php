<?php

namespace App\Policies;

use App\Models\User;
use TCG\Voyager\Policies\UserPolicy as TcgUserPolicy;

class UserPolicy extends TcgUserPolicy
{
    /**
     * @param User $user
     */
    public function viewHorizon(User $user)
    {
        if($user->hasRole('admin'))
        {
            return true;
        }

        return false;
    }
}
