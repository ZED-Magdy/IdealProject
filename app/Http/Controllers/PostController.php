<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

#[
    Group('Posts'),
    Authenticated
]
class PostController extends Controller
{
    public function __construct(protected readonly PostService $service)
    {
    }

    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection<PostResource>
     * @throws AuthorizationException
     */
    #[ResponseFromApiResource(PostResource::class, Post::class,  200, 'list of posts', true, with: ['user'], simplePaginate: 20)]
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Post::class);

        $posts = $this->service->listPosts(perPage: 20);

        return PostResource::collection($posts);
    }


    /**
     * Store a newly created resource in storage.
     * @param StorePostRequest $request
     * @return PostResource
     * @throws AuthorizationException
     */
    #[ResponseFromApiResource(PostResource::class, Post::class,  201, 'new post', with: ['user'])]
    public function store(StorePostRequest $request): PostResource
    {
        $this->authorize('create', Post::class);

        /**
         * @var User $user
         */
        $user = $request->user();

        $post = $this->service->create(
            $request->string('content')->value(),
            $user
        );

        return new PostResource($this->service->show($post));
    }

    /**
     * Display the specified resource.
     * @param Post $post
     * @return PostResource
     * @throws AuthorizationException
     */
    #[ResponseFromApiResource(PostResource::class, Post::class,  200, 'show post', with: ['user'])]
    public function show(Post $post): PostResource
    {
        $this->authorize('view', $post);

        return new PostResource($this->service->show($post));
    }


    /**
     * Update the specified resource in storage.
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return PostResource
     * @throws AuthorizationException
     */
    #[ResponseFromApiResource(PostResource::class, Post::class,  200, 'update post', with: ['user'])]
    #[\Knuckles\Scribe\Attributes\Response(
        [
            'message' => 'Unauthorized',
        ],
        403,
        'User cannot update post that doesnt belong to him.',
    )]
    public function update(UpdatePostRequest $request, Post $post): PostResource
    {
        $this->authorize('update', $post);
        $post = $this->service->update($post, $request->string('content')->value());

        return new PostResource($this->service->show($post));
    }

    /**
     * Remove the specified resource from storage.
     * @param Post $post
     * @return Response
     * @throws AuthorizationException
     */
    #[\Knuckles\Scribe\Attributes\Response(
        [],
        204,
        'Post deleted successfully.',
    )]
    #[\Knuckles\Scribe\Attributes\Response(
        [
            'message' => 'Unauthorized',
        ],
        403,
        'User cannot delete post that doesnt belong to him.',
    )]
    public function destroy(Post $post): Response
    {
        $this->authorize('delete', $post);

        $this->service->delete($post);

        return response()->noContent();
    }
}
