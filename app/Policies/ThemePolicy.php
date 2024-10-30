<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Theme;

class ThemePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Theme $topic): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if (!is_null($user->permissions)) {
            return $user->hasAccess('platform.resource.add');
        }

        return $user->exists;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Theme $topic): bool
    {
        if (!is_null($user->permissions)) {
            return $user->hasAccess('platform.resource.edit');
        }

        return $user->id == $topic->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Theme $topic): bool
    {
        if (!is_null($user->permissions)) {
            return $user->hasAccess('platform.resource.destroy');
        }

        return $user->id == $topic->user_id;
    }
}
