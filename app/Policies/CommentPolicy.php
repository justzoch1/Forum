<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use App\Traits\AdminPanel;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class CommentPolicy
{
    use AdminPanel;

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
    public function view(User $user, Comment $comment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($this->isAdminPanel()) {
            return $user->hasAccess('platform.resource.add');
        }

        return $user->exists;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        if ($this->isAdminPanel()) {
            return $user->hasAccess('platform.resource.edit');
        }

        return $user->id == $comment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        if ($this->isAdminPanel()) {
            return $user->hasAccess('platform.resource.destroy');
        }

        return $user->id == $comment->user_id;
    }
}
