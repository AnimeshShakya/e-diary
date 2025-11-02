<?php

namespace App\Policies;

use App\Models\Diary;
use App\Models\User;

class DiaryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view their own diaries
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Diary $diary): bool
    {
        // Users can view their own diaries, admins can view all
        return $user->id === $diary->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create diary entries
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Diary $diary): bool
    {
        // Users can only update their own diaries
        return $user->id === $diary->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Diary $diary): bool
    {
        // Users can delete their own diaries, admins can delete any
        return $user->id === $diary->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Diary $diary): bool
    {
        // Users can restore their own diaries, admins can restore any
        return $user->id === $diary->user_id || $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Diary $diary): bool
    {
        // Only admins can force delete diaries
        return $user->isAdmin();
    }
}
