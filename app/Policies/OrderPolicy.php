<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view any orders.
     */
    public function viewAny(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the order.
     */
    public function view(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create orders.
     */
    public function create(User $user): bool
    {
        return in_array($user->role->id, [Role::ADMIN->value, Role::SALES_ASSISTANT->value]);
    }

    /**
     * Determine whether the user can update the order.
     */
    public function update(User $user): bool
    {
        return in_array($user->role->id, [Role::ADMIN->value, Role::SALES_ASSISTANT->value]);
    }

    /**
     * Determine whether the user can delete the order.
     */
    public function delete(User $user): bool
    {
        return in_array($user->role->id, [Role::ADMIN->value, Role::SALES_ASSISTANT->value]);
    }
}
