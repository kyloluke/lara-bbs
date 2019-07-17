<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{
    // https://learnku.com/docs/laravel/5.8/authorization/3908#policy-methods
    public function update(User $user, Topic $topic)
    {
        return $user->isAuthorOf($topic);
    }


    public function delete(User $user, Topic $topic)
    {
        return $user->isAuthorOf($topic);
    }
}
