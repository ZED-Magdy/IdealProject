<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Vote;
use App\Services\VoteService;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

#[
    Group('Votes'),
    Authenticated
]
class VoteController extends Controller
{
    public function __construct(protected readonly VoteService $service)
    {
    }

    /**
     * Upvote or undo upvote a post.
     *
     * @param VoteRequest $request
     * @return PostResource
     */
    #[ResponseFromApiResource(PostResource::class, Post::class, 200, 'post with upvotes and downvotes', with: ['user'])]
    public function upvoteTogglePost(VoteRequest $request)
    {

        $user = $request->user();

        $post = $this->service->upvoteTogglePost($request->integer('post_id'), $user);

        return new PostResource($post);
    }

    /**
     * Downvote or undo downvote a post.
     *
     * @param VoteRequest $request
     * @return PostResource
     */

    #[ResponseFromApiResource(PostResource::class, Post::class, 200, 'post with upvotes and downvotes', with: ['user'])]
    public function downvoteTogglePost(VoteRequest $request)
    {

        $user = $request->user();

        $post = $this->service->downvoteTogglePost($request->integer('post_id'), $user);

        return new PostResource($post);
    }




}
