<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection<PostResource>
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Post::class);

        $posts = Post::query()->with(['user'])->latest()->simplePaginate(20);

        return PostResource::collection($posts);
    }


    /**
     * Store a newly created resource in storage.
     * @param StorePostRequest $request
     * @return PostResource
     * @throws AuthorizationException
     */
    public function store(StorePostRequest $request): PostResource
    {
        $this->authorize('create', Post::class);

        /**
         * @var User $user
         */
        $user = $request->user();

        /**
         * @var Post $post
         */
        $post = $user->posts()->create($request->validated());

        return new PostResource($post->load(['user']));
    }

    /**
     * Display the specified resource.
     * @param Post $post
     * @return PostResource
     * @throws AuthorizationException
     */
    public function show(Post $post): PostResource
    {
        $this->authorize('view', $post);

        return new PostResource($post->load(['user']));
    }


    /**
     * Update the specified resource in storage.
     * @param UpdatePostRequest $request
     * @param Post $post
     * @return PostResource
     * @throws AuthorizationException
     */
    public function update(UpdatePostRequest $request, Post $post): PostResource
    {
        $this->authorize('update', $post);

        $post->update($request->validated());

        return new PostResource($post->load(['user']));
    }

    /**
     * Remove the specified resource from storage.
     * @param Post $post
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(Post $post): Response
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->noContent();
    }
}
