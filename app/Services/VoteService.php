<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;

final readonly class VoteService
{

    /**
     * Upvote or undo upvote a post.
     *
     * @param  int  $post_id
     * @param  User  $user
     * @return Post
     */
    public function upvoteTogglePost(int $post_id, User $user): Post
    {
     $post = Post::find($post_id);

     $post->upVoteToggle($user);

        return $post;

    }

    /**
     * Downvote or undo downvote a post.
     *
     * @param  int  $post_id
     * @param  User  $user
     * @return Post
     */
    public function downvoteTogglePost(int $post_id, User $user): Post
    {
     $post = Post::find($post_id);

     $post->downVoteToggle($user);

        return $post;
    }


}
