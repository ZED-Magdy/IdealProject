<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Model;

/**
 * @method morphMany(string $class, string $string)
 * @method where(string $string, $id)
 * @method create(array $array)
 * @method update(array $array)
 * @method delete()
 * @method count()
 * @method upVotes()
 * @method downVotes()
 */
trait Votable
{

    /**
     * @return mixed
     */
    public function votes(): mixed
    {
        return $this->morphMany(Vote::class, 'votable');
    }


    /**
     * @return int
     */
    public function votesCount(): int
    {
        return $this->votes()->count();
    }


    /**
     * @return int
     */
    public function upVotesCount(): int
    {
        return $this->upVotes()->count();
    }


    /**
     * @return int
     */
    public function downVotesCount(): int
    {
        return $this->downVotes()->count();
    }


    /**
     * @return int
     */
    public function voteResult(): int
    {
        return $this->upVotesCount() - $this->downVotesCount();
    }


    /**
     * @return bool
     */
    public function isUpvoted(): bool
    {
        return $this->votes()->where('user_id', auth()->user()->id)->where('vote', 1)->exists();
    }

    /**
     * @return bool
     */
    public function isDownvoted(): bool
    {
        return $this->votes()->where('user_id', auth()->user()->id)->where('vote', -1)->exists();
    }


    /**
     * @param  User  $user
     * @return void
     */
    public function upVoteToggle(User $user): void
    {

        $vote = $this->votes()->where('user_id', $user->id)->first();
        if ($vote) { //undo upvote or change to upvote

            if ($vote->vote) {
                $vote->delete();
            } else {
                $vote->update([
                    'vote' => 1
                ]);
            }
        } else { //upvote
            $this->votes()->create([
                'user_id' => $user->id,
                'vote' => 1
            ]);
        }

    }


    /**
     * @param  User  $user
     * @return void
     */
    public function downVoteToggle(User $user): void
    {
        $vote = $this->votes()->where('user_id', $user->id)->first();
        if ($vote) { //undo downvote or change to downvote
            if ($vote->vote) {
                $vote->update([
                    'vote' => -1
                ]);
            } else {
                $vote->delete();
            }
        } else { //downvote
            $this->votes()->create([
                'user_id' => $user->id,
                'vote' => -1
            ]);
        }
    }

}
