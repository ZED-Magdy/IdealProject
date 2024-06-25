<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteCommentRequest;
use App\Http\Requests\VotePostRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Vote;
use App\Services\CommentService;
use App\Services\PostService;
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
    public function __construct(protected readonly VoteService $service , protected readonly CommentService $commentService , protected readonly PostService $postService)
    {
    }

    /**
     * Upvote or undo upvote a post.
     *
     * @param VotePostRequest $request
     * @return PostResource
     */
    #[ResponseFromApiResource(PostResource::class, Post::class, 200, 'upvote or undo upvote post', with: ['user'])]
    public function upvoteTogglePost(VotePostRequest $request)
    {

        $user = $request->user();

        $post = $this->service->upvoteTogglePost($request->integer('post_id'), $user);

        return new PostResource($this->postService->show($post));
    }

    /**
     * Downvote or undo downvote a post.
     *
     * @param VotePostRequest $request
     * @return PostResource
     */
    #[ResponseFromApiResource(PostResource::class, Post::class, 200, 'Downvote or undo downvote a post.', with: ['user'])]
    public function downvoteTogglePost(VotePostRequest $request)
    {

        $user = $request->user();

        $post = $this->service->downvoteTogglePost($request->integer('post_id'), $user);

        return new PostResource($this->postService->show($post));
    }

    /**
     * Upvote or undo upvote a comment.
     *
     * @param VoteCommentRequest $request
     * @return CommentResource
     */
    #[ResponseFromApiResource(CommentResource::class, Comment::class, 200, 'Upvote or undo upvote a comment.', with: ['user'])]
    public function upvoteToggleComment(VoteCommentRequest $request)
    {

        $user = $request->user();

        $comment = $this->service->upvoteToggleComment($request->integer('comment_id'), $user);

        return new CommentResource($this->commentService->show($comment));
    }

    /**
     * Downvote or undo downvote a comment.
     * @param  VoteCommentRequest  $request
     * @return CommentResource
     */
    #[ResponseFromApiResource(CommentResource::class, Comment::class, 200, 'Downvote or undo downvote a comment.', with: ['user'])]
    public function downvoteToggleComment(VoteCommentRequest $request)
    {

        $user = $request->user();

        $comment = $this->service->downvoteToggleComment($request->integer('comment_id'), $user);

        return new CommentResource($this->commentService->show($comment));
    }






}
