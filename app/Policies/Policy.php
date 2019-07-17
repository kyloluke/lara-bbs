<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    // 策略过滤机制  https://learnku.com/docs/laravel/5.8/authorization/3908#policy-filters

    public function before(User $user, $ability)
    {
        if ($user->can('manage_contents')) {
            return true;
        }
    }
}
