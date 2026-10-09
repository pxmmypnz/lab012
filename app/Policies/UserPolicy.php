<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    function manage(User $user): bool
    {
        return $user->isAdministrator();
    }

    function delete(User $user, User $target): bool
    {
        return $user->isAdministrator() && $user->isNot($target);
    }
}
