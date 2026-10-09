<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    function list(User $user): bool
    {
        return true;
    }

    function view(User $user, Product $product): bool
    {
        return $this->list($user);
    }

    function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    function update(User $user, Product $product): bool
    {
        return $this->create($user);
    }

    function delete(User $user, Product $product): bool
    {
        return $this->update($user, $product);
    }
}
