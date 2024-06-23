<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class VoteService
{

    /**
     * Upvote or undo upvote a post.
     *
     * @param  int  $post_id
     * @param  ?User  $user
     * @return Post
     * @throws NotFoundHttpException
     */
    public function upvoteTogglePost(int $post_id, ?User $user): Post
    {
     $post = Post::find($post_id);

     if(!$post){
         throw new NotFoundHttpException('Post not found');
     }

     $post->upvoteToggle($user);

        return $post;

    }

    /**
     * Downvote or undo downvote a post.
     *
     * @param  int  $post_id
     * @param  ?User  $user
     * @return Post
     * @throws NotFoundHttpException
     */
    public function downvoteTogglePost(int $post_id, ?User $user): Post
    {
     $post = Post::find($post_id);

        if(!$post){
            throw new NotFoundHttpException('Post not found');
        }

     $post->downvoteToggle($user);

        return $post;
    }


}
