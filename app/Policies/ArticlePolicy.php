<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    /**
     * Determine whether the user can view any articles.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the article.
     */
    public function view(?User $user, Article $article): bool
    {
        // Anyone can view published articles
        if ($article->status === 'published') {
            return true;
        }

        // Only authenticated users can view unpublished articles
        if (!$user) {
            return false;
        }

        // Authors can view their own articles
        if ($user->id === $article->user_id) {
            return true;
        }

        // Moderators and admins can view all articles
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can create articles.
     */
    public function create(User $user): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Check if user has permission to create articles
        return $user->hasPermissionTo('create articles');
    }

    /**
     * Determine whether the user can update the article.
     */
    public function update(User $user, Article $article): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Authors can edit their own articles
        if ($user->id === $article->user_id) {
            return $user->hasPermissionTo('edit own articles');
        }

        // Editors and above can edit any article
        return $user->can('edit all articles');
    }

    /**
     * Determine whether the user can delete the article.
     */
    public function delete(User $user, Article $article): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Authors can delete their own articles if they have permission
        if ($user->id === $article->user_id) {
            return $user->hasPermissionTo('delete own articles');
        }

        // Moderators and admins can delete any article
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can restore the article.
     */
    public function restore(User $user, Article $article): bool
    {
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can permanently delete the article.
     */
    public function forceDelete(User $user, Article $article): bool
    {
        return $user->can('admin access');
    }

    /**
     * Determine whether the user can moderate the article.
     */
    public function moderate(User $user): bool
    {
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can comment on the article.
     */
    public function comment(User $user, Article $article): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Check if article allows comments
        if (!$article->allow_comments) {
            return false;
        }

        // Check if user has permission to comment
        return $user->hasPermissionTo('create comments');
    }

    /**
     * Determine whether the user can publish the article.
     */
    public function publish(User $user, ?Article $article = null): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // If no article is provided (create case), check general publish permission
        if (!$article) {
            return $user->hasPermissionTo('publish articles') || $user->hasRole(['editor', 'moderator', 'admin']);
        }

        // Authors can publish their own articles if they have general publish permission
        if ($user->id === $article->user_id) {
            return $user->hasPermissionTo('publish articles');
        }

        // Editors and above can publish any article
        return $user->can('edit all articles');
    }

    /**
     * Determine whether the user can feature the article.
     */
    public function feature(User $user, ?Article $article = null): bool
    {
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can change article status.
     */
    public function changeStatus(User $user): bool
    {
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can view article revisions.
     */
    public function viewRevisions(User $user, Article $article): bool
    {
        // Authors can view their own article revisions
        if ($user->id === $article->user_id) {
            return true;
        }

        // Editors and above can view any article revisions
        return $user->can('edit all articles');
    }

    /**
     * Determine whether the user can restore article revisions.
     */
    public function restoreRevision(User $user, Article $article): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Authors can restore their own article revisions
        if ($user->id === $article->user_id) {
            return $user->hasPermissionTo('edit own articles');
        }

        // Editors and above can restore any article revision
        return $user->can('edit all articles');
    }
}