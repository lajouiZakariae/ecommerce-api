<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role->id, [Role::ADMIN->value, Role::PRODUCTS_MANAGER->value]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return in_array($user->role->id, [Role::ADMIN->value, Role::PRODUCTS_MANAGER->value]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return in_array($user->role->id, [Role::ADMIN->value, Role::PRODUCTS_MANAGER->value]);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return in_array($user->role->id, [Role::ADMIN->value, Role::PRODUCTS_MANAGER->value]);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return in_array($user->role->id, [Role::ADMIN->value, Role::PRODUCTS_MANAGER->value]);
    }
}
