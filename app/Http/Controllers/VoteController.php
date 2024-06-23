<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\VoteService;

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
    public function upvoteTogglePost(VoteRequest $request)
    {
        $this->authorize('create', Post::class);
        $this->authorize('update', Post::class);
        $this->authorize('delete', Post::class);

        $user = $request->user();

        $post = $this->service->upvoteTogglePost($request->post_id, $user);

        return new PostResource($post);


    }

    /**
     * Downvote or undo downvote a post.
     *
     * @param VoteRequest $request
     * @return PostResource
     */

    public function downvoteTogglePost(VoteRequest $request)
    {
        $this->authorize('create', Post::class);
        $this->authorize('update', Post::class);
        $this->authorize('delete', Post::class);

        $user = $request->user();

        $post = $this->service->downvoteTogglePost($request->post_id, $user);

        return new PostResource($post);
    }




}
