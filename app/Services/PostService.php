<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

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
     * @param UploadedFile|null $media
     * @return Post
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function create(string $content, User $user, UploadedFile|null $media = null): Post
    {
        /**
         * @var Post $post
         */
        $post =  $user->posts()->create([
            'content' => $content
        ]);

        if($media) {
            $post->addMedia($media)->toMediaCollection('posts');
        }

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
        return $post->load(['user', 'media']);
    }

    /**
     * Update a post.
     *
     * @param Post $post
     * @param string $content
     * @param UploadedFile|null $media
     * @return Post
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(Post $post, string $content, UploadedFile|null $media): Post
    {
        $post->update([
            'content' => $content
        ]);

        if($media) {
            $post->clearMediaCollection('posts');
            $post->addMedia($media)->toMediaCollection('posts');
        }

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
