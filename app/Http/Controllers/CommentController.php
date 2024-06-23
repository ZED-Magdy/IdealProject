<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

#[
    Group('Comments'),
    Authenticated
]

class CommentController extends Controller
{

    public function __construct(protected readonly CommentService $service)
    {
    }

    /**
     * Display a listing of the resource.
     * @param Post $post
     * @return AnonymousResourceCollection<CommentResource>
     */

    #[ResponseFromApiResource(CommentResource::class, Comment::class,  200, 'list of comments', true, with: ['user'], simplePaginate: 20)]

    public function index(Post $post): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Comment::class);

        $comments = $this->service->listComments(perPage: 20, post: $post);

        return CommentResource::collection($comments);
    }


    /**
     * Display the specified resource.
     * @param Comment $comment
     * @return CommentResource
     */

    #[ResponseFromApiResource(PostResource::class, Post::class,  200, 'show comment', with: ['user'])]
    public function show(Comment $comment): CommentResource
    {

        $this->authorize('view', $comment);

        $comment = $this->service->show($comment);

        return new CommentResource($comment);
    }


    /**
     * Store a newly created resource in storage.
     * @param CommentRequest $request
     * @param Post $post
     * @return CommentResource
     */

    #[ResponseFromApiResource(CommentResource::class, Comment::class,  201, 'new comment', with: ['user'])]
    public function store(CommentRequest $request, Post $post): CommentResource
    {
        $this->authorize('create', Comment::class);

        $comment = $this->service->create(title: $request->string('title')->value(), post: $post);

        return new CommentResource($this->service->show($comment));
    }

    /**
     * Update the specified resource in storage.
     * @param CommentRequest $request
     * @param Comment $comment
     * @return CommentResource
     */

    #[ResponseFromApiResource(CommentResource::class, Comment::class,  200, 'update comment', with: ['user'])]
    #[\Knuckles\Scribe\Attributes\Response(
        [
            'message' => 'Unauthorized',
        ],
        403,
        'User cannot update comment that doesnt belong to him.',
    )]
    public function update(CommentRequest $request, Comment $comment): CommentResource
    {

        $this->authorize('update', $comment);

        $comment = $this->service->update(title: $request->string('title')->value(), comment: $comment);

        return new CommentResource($this->service->show($comment));
    }


    /**
     * Remove the specified resource from storage.
     * @param Comment $comment
     * @return JsonResponse
     */

    #[\Knuckles\Scribe\Attributes\Response(
        [],
        204,
        'Comment deleted successfully.',
    )]
    #[\Knuckles\Scribe\Attributes\Response(
        [
            'message' => 'Unauthorized',
        ],
        403,
        'User cannot delete comment that doesnt belong to him.',
    )]
    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete',$comment );

        $this->service->delete($comment);

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }


}
