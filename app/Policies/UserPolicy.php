<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends Policy
{
    // https://learnku.com/docs/laravel/5.8/authorization/3908#policy-methods
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }
}
