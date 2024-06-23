<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Contracts\Pagination\Paginator;

final readonly class CommentService
{

    /**
     * List comments.
     *
     * @param int $perPage
     * @return Paginator<Comment>
     */
    public function listComments(int $perPage, Post $post): Paginator
    {
        return Comment::query()
            ->where('post_id', $post->id)
            ->with(['post', 'user'])->latest()->simplePaginate($perPage);
    }

    /**
     * Show a comment.
     *
     * @param int $commentId
     * @return Comment
     */

    public function show(int $commentId): Comment
    {
        return Comment::query()->findOrFail($commentId)->load(['post', 'user']);
    }

    /**
     * Create a new comment.
     *
     * @param string $title
     * @param Post $post
     * @return Comment
     */
    public function create(string $title, Post $post): Comment
    {
        /**
         * @var Comment $comment
         */
        $comment = $post->comments()->create([
            'title' => $title,
            'user_id' => auth()->id(),
            'post_id' => $post->id,
        ]);

        return $comment->load(['post', 'user']);
    }

    /**
     * Update a comment.
     *
     * @param string $title
     * @param Comment $comment
     * @return  Comment
     */
    public function update(string $title, Comment $comment): Comment
    {

        $comment->title = $title;
        $comment->save();

        return $comment->load(['post', 'user']);
    }

    /**
     * Delete a comment.
     *
     * @param Comment $comment
     * @return void
     */
    public function delete(Comment $comment): void
    {
        $comment->delete();
    }

}
