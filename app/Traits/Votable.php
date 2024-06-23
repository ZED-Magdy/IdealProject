<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @method where(string $string, $id)
 * @method create(array $array)
 * @method update(array $array)
 * @method delete()
 * @method count()

 */
trait Votable
{

    /**
     * return all votes
     * @return MorphMany<Vote>
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    /**
     * @return  MorphMany<Vote>
     */

    public function upVotes(): MorphMany
    {
        return $this->morphMany(Vote::class,'votable')->where('vote', true);
    }

    /**
     * @return MorphMany<Vote>
     */

    public function downVotes(): MorphMany
    {
        return $this->morphMany(Vote::class,'votable')->where('vote', false);
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
     * @return Attribute<string,void>
     */
    public function vote(): Attribute
    {

        /**
         * @var bool|null $vote
         */
        $vote = $this->votes()->where('user_id', auth()->user()?->id)->first()?->vote;

        $result = match ($vote) {
            true => 'up_vote',
            false => 'down_vote',
            default => 'no_vote'
        };

        return Attribute::make(
            fn() => $result
        );
    }


    /**
     * @param  ?User  $user
     * @return void
     */
    public function upvoteToggle(?User $user): void
    {
        if(!$user){
            return;
        }

        $vote = $this->votes()->where('user_id', $user->id)->first();
        if ($vote) { //undo upvote or change to upvote
            if ($vote->vote) {
                $vote->delete();
            } else {
                $vote->update([
                    'vote' => true
                ]);
            }
        } else { //upvote
            $this->votes()->create([
                'user_id' => $user->id,
                'vote' => true
            ]);
        }

    }


    /**
     * @param  ?User  $user
     * @return void
     */
    public function downvoteToggle(?User $user): void
    {

        if (!$user) {
            return;
        }

        $vote = $this->votes()->where('user_id', $user->id)->first();
        if ($vote) { //undo downvote or change to downvote

                if (!$vote->vote) {
                    $vote->delete();
                } else {
                    $vote->update([
                        'vote' => false
                    ]);
                }
        } else { //downvote
            $this->votes()->create([
                'user_id' => $user->id,
                'vote' => false
            ]);
        }
    }

}
