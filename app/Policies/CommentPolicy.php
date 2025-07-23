<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can view any comments.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the comment.
     */
    public function view(?User $user, Comment $comment): bool
    {
        // Anyone can view approved comments
        if ($comment->status === 'approved') {
            return true;
        }

        // Only authenticated users can view non-approved comments
        if (!$user) {
            return false;
        }

        // Authors can view their own comments
        if ($user->id === $comment->user_id) {
            return true;
        }

        // Moderators and admins can view all comments
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can create comments.
     */
    public function create(User $user): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Check if user has permission to create comments
        return $user->hasPermissionTo('create comments');
    }

    /**
     * Determine whether the user can update the comment.
     */
    public function update(User $user, Comment $comment): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Comments can only be edited within 15 minutes of creation
        if ($comment->created_at->diffInMinutes(now()) > 15) {
            // Unless user has moderation permissions
            if (!$user->hasPermissionTo('moderate content')) {
                return false;
            }
        }

        // Authors can edit their own comments
        if ($user->id === $comment->user_id) {
            return $user->hasPermissionTo('edit own comments');
        }

        // Moderators and admins can edit any comment
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can delete the comment.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Authors can delete their own comments
        if ($user->id === $comment->user_id) {
            return $user->hasPermissionTo('delete own comments');
        }

        // Moderators and admins can delete any comment
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can restore the comment.
     */
    public function restore(User $user, Comment $comment): bool
    {
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can permanently delete the comment.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return $user->can('access admin panel');
    }

    /**
     * Determine whether the user can moderate comments.
     */
    public function moderate(User $user): bool
    {
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can approve comments.
     */
    public function approve(User $user, Comment $comment): bool
    {
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can reject comments.
     */
    public function reject(User $user, Comment $comment): bool
    {
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can mark comments as spam.
     */
    public function markAsSpam(User $user, Comment $comment): bool
    {
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can report comments.
     */
    public function report(User $user, Comment $comment): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Users cannot report their own comments
        if ($user->id === $comment->user_id) {
            return false;
        }

        // Any authenticated user can report comments
        return true;
    }

    /**
     * Determine whether the user can like comments.
     */
    public function like(User $user, Comment $comment): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Users cannot like their own comments
        if ($user->id === $comment->user_id) {
            return false;
        }

        // Any authenticated user can like comments
        return true;
    }

    /**
     * Determine whether the user can reply to comments.
     */
    public function reply(User $user, Comment $comment): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Check if user has permission to create comments
        return $user->hasPermissionTo('create comments');
    }

    /**
     * Determine whether the user can bulk moderate comments.
     */
    public function bulkModerate(User $user): bool
    {
        return $user->can('moderate content');
    }
}