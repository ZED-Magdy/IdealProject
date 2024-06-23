<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;

final readonly class PostService
{
    /**
     * List posts.
     *
     * @param int $perPage
     * @return Paginator<Post>
     */
    public function listPosts(int $perPage): Paginator
    {
        return Post::query()->with(['user'])->latest()->simplePaginate($perPage);
    }

    /**
     * Create a new post.
     *
     * @param string $content
     * @param User $user
     * @return Post
     */
    public function create(string $content, User $user): Post
    {
        /**
         * @var Post $post
         */
        $post =  $user->posts()->create([
            'content' => $content
        ]);

        return $post;
    }

    /**
     * Show a post.
     *
     * @param Post $post
     * @return Post
     */
    public function show(Post $post): Post
    {
        return $post->load(['user']);
    }

    /**
     * Update a post.
     *
     * @param Post $post
     * @param string $content
     * @return Post
     */
    public function update(Post $post, string $content): Post
    {
        $post->update([
            'content' => $content
        ]);

        return $post;
    }

    /**
     * Delete a post.
     *
     * @param Post $post
     */
    public function delete(Post $post): void
    {
        $post->delete();
    }


}
