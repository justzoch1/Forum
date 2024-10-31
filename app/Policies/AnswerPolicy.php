<?php

namespace App\Policies;

use App\Models\Answer;
use App\Models\User;
use App\Traits\AdminPanel;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class AnswerPolicy
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
    public function view(User $user, Answer $answer): bool
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
    public function update(User $user, Answer $answer): bool
    {
        if ($this->isAdminPanel()) {
            return $user->hasAccess('platform.resource.edit');
        }

        Log::info(['user' => $user->id, 'answer' => $answer->user_id]);
        return $user->id == $answer->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Answer $answer): bool
    {
        if ($this->isAdminPanel()) {
            return $user->hasAccess('platform.resource.destroy');
        }

        return $user->id == $answer->user_id;
    }
}
