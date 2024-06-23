<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Services\PostService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
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
    #[ResponseFromApiResource(PostResource::class, Post::class,  200, 'list of posts', true, with: ['user', 'media'], simplePaginate: 20)]
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Post::class);

        $posts = $this->service->listPosts(perPage: 20);

        return PostResource::collection($posts);
    }


    /**
     * Store a newly created resource in storage.
     * @param StorePostRequest $request
     * @return PostResource|JsonResponse
     * @throws AuthorizationException
     */
    #[ResponseFromApiResource(PostResource::class, Post::class,  201, 'new post', with: ['user', 'media'])]
    public function store(StorePostRequest $request): PostResource|JsonResponse
    {
        $this->authorize('create', Post::class);

        try {
            /**
             * @var User $user
             */
            $user = $request->user();
            /**
             * @var UploadedFile|null $media
             */
            $media = $request->file('media');
            $post = $this->service->create(
                $request->string('content')->value(),
                $user,
                $media
            );

            return new PostResource($this->service->show($post));
        }catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong'], 400);
        }
    }

    /**
     * Display the specified resource.
     * @param Post $post
     * @return PostResource
     * @throws AuthorizationException
     */
    #[ResponseFromApiResource(PostResource::class, Post::class,  200, 'show post', with: ['user', 'media'])]
    public function show(Post $post): PostResource
    {
        $this->authorize('view', $post);

        return new PostResource($this->service->show($post));
    }


    /**
     * Update the specified resource in storage.
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return PostResource|JsonResponse
     * @throws AuthorizationException
     */
    #[ResponseFromApiResource(PostResource::class, Post::class,  200, 'update post', with: ['user', 'media'])]
    #[\Knuckles\Scribe\Attributes\Response(
        [
            'message' => 'Unauthorized',
        ],
        403,
        'User cannot update post that doesnt belong to him.',
    )]
    public function update(UpdatePostRequest $request, Post $post): PostResource|JsonResponse
    {
        $this->authorize('update', $post);
        try {
            /**
             * @var UploadedFile|null $media
             */
            $media = $request->file('media');
            $post = $this->service->update(
                $post,
                $request->string('content')->value(),
                $media
            );

            return new PostResource($this->service->show($post));
        }catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong'], 400);
        }
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
