<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    /**
     * Determine whether the user can view any categories.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the category.
     */
    public function view(?User $user, Category $category): bool
    {
        // Anyone can view active categories
        if ($category->is_active) {
            return true;
        }

        // Only moderators and admins can view inactive categories
        return $user && $user->can('moderate content');
    }

    /**
     * Determine whether the user can create categories.
     */
    public function create(User $user): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Check if user has permission to create categories
        return $user->hasPermissionTo('create categories');
    }

    /**
     * Determine whether the user can update the category.
     */
    public function update(User $user, Category $category): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Check if user has permission to edit categories
        return $user->hasPermissionTo('edit categories');
    }

    /**
     * Determine whether the user can delete the category.
     */
    public function delete(User $user, Category $category): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Check if user has permission to delete categories
        return $user->hasPermissionTo('delete categories');
    }

    /**
     * Determine whether the user can restore the category.
     */
    public function restore(User $user, Category $category): bool
    {
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can permanently delete the category.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        return $user->can('access admin panel');
    }

    /**
     * Determine whether the user can moderate categories.
     */
    public function moderate(User $user): bool
    {
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can manage category ordering.
     */
    public function manageOrdering(User $user): bool
    {
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can toggle category active status.
     */
    public function toggleActive(User $user, Category $category): bool
    {
        return $user->can('moderate content');
    }

    /**
     * Determine whether the user can assign articles to categories.
     */
    public function assignArticles(User $user): bool
    {
        // Check if user is banned
        if ($user->isBanned()) {
            return false;
        }

        // Contributors and above can assign articles to categories
        return $user->can('create articles');
    }
}