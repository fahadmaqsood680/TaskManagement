<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function view(User $user,Category $category): bool
    {
        return $user->role === 'admin' || $category->user_id === $user->id;
    }

    

}
