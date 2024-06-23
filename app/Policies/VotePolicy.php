<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vote;
use Illuminate\Auth\Access\HandlesAuthorization;

class VotePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Vote $vote): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Vote $vote): bool
    {
        return $user->id === $vote->user_id;
    }

    public function delete(User $user, Vote $vote): bool
    {
        return $user->id === $vote->user_id;
    }
}
