<?php

namespace App\Policies;

use App\Models\Shop;
use App\Models\User;

class ShopPolicy
{
    function list(User $user): bool
    {
        return true;
    }

    function view(User $user, Shop $shop): bool
    {
        return true;
    }

    function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    function update(User $user, Shop $shop): bool
    {
        return $user->isAdministrator();
    }

    function delete(User $user, Shop $shop): bool
    {
        return $this->update($user, $shop);
    }
}
