<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    function updateRole(User $user, User $target): bool
    {
        return $user->isAdministrator() && $user->isNot($target);
    }

    function manage(User $user): bool
    {
        return $user->isAdministrator();
    }

    function delete(User $user, User $target): bool
    {
        return $user->isAdministrator() && $user->isNot($target);
    }

}
