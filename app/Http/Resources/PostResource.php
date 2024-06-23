<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
/** @mixin Post
 * @property-read string $vote
 * */
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'user' => $this->whenLoaded('user', fn() => new UserResource($this->user)),
            'vote' => $this->vote,
            'votes_result' => $this->voteResult(),
            'created_at' => $this->created_at,
        ];
    }
}
