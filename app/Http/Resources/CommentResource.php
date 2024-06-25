<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Comment
 * @property-read string $vote
 * @mixin \App\Models\Vote
 * */
class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id' => $this->id,
            'title' => $this->title,
            'vote' => $this->vote,
            'votes_result' => $this->voteResult(),
            'user' => $this->whenLoaded('user', fn() => new UserResource($this->user)),
        ];
    }
}
