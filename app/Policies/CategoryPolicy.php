<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    function list(User $user): bool
    {
        return true;
    }

    function view(User $user, Category $category): bool
    {
        return true;
    }

    function create(User $user): bool
    {
        return $user->isAdministrator();
    }

    function update(User $user, Category $category): bool
    {
        return $user->isAdministrator();
    }

    function delete(User $user, Category $category): bool
    {
        $category->loadCount('products');

        return $this->update($user, $category) && $category->products_count === 0;
    }
}
