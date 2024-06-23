<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * App\Models\Vote
 *
 * @property int $id
 * @property int $vote
 * @property string $votable
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\VoteFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Vote query()
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereVotable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vote whereVote($value)
 * @mixin \Eloquent
 */

class Vote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['vote', 'votable', 'user_id'];

    protected $casts = [
        'vote' => 'boolean'
    ];

    /**
     * Get the user that owns the Vote
     *
     * @return BelongsTo<User, Vote>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the owning votable model
     * @return MorphTo<Model, Vote>
     */

    public function votable(): MorphTo
    {
        return $this->morphTo();
    }
}
