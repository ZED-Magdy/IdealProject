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

    }

    public function view(User $user, Vote $vote): bool
    {
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Vote $vote): bool
    {
    }

    public function delete(User $user, Vote $vote): bool
    {
    }

    public function restore(User $user, Vote $vote): bool
    {
    }

    public function forceDelete(User $user, Vote $vote): bool
    {
    }
}
