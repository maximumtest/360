<?php

namespace App\Policies;

use App\User;
use App\Kudos;
use Illuminate\Auth\Access\HandlesAuthorization;

class KudosPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function store(User $user)
    {
        return request()->route('user_to')->id !== $user->id;
    }

    public function update(User $user, Kudos $kudos)
    {
        return $user->id === $kudos->user_from_id;
    }

    public function delete(User $user, Kudos $kudos)
    {
        return $user->id === $kudos->user_from_id;
    }
}
