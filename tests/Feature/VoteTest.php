<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

test('user can upvote a post', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $post = Post::factory()->create();
    $response = $this->postJson('/api/post/upvote', ['post_id' => $post->id]);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toHaveKeys([
            'id', 'content', 'created_at', 'vote', 'votes_result'
        ])
        ->and($response->json('data.vote'))->toBe('up_vote')
        ->and($response->json('data.votes_result'))->toBe(1);
});

test('user can downvote a post', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $post = Post::factory()->create();
    $response = $this->postJson('/api/post/downvote', ['post_id' => $post->id]);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toHaveKeys([
            'id', 'content', 'created_at', 'vote', 'votes_result'
        ])
        ->and($response->json('data.vote'))->toBe('down_vote')
        ->and($response->json('data.votes_result'))->toBe(-1);
});

test('user can undo upvote a post', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $post = Post::factory()->create();
    $post->upvoteToggle($user);
    $response = $this->postJson('/api/post/upvote', ['post_id' => $post->id]);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toHaveKeys([
            'id', 'content', 'created_at', 'vote', 'votes_result'
        ])
        ->and($response->json('data.vote'))->toBe('no_vote')
        ->and($response->json('data.votes_result'))->toBe(0);
});

test('user can undo downvote a post', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $post = Post::factory()->create();
    $post->downvoteToggle($user);

    $response = $this->postJson('/api/post/downvote', ['post_id' => $post->id]);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toHaveKeys([
            'id', 'content', 'created_at', 'vote', 'votes_result'
        ])
        ->and($response->json('data.vote'))->toBe('no_vote')
        ->and($response->json('data.votes_result'))->toBe(0);
});

test('replace downvote with upvote when upvote is clicked', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $post = Post::factory()->create();
    $post->downvoteToggle($user);

    $response = $this->postJson('/api/post/upvote', ['post_id' => $post->id]);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toHaveKeys([
            'id', 'content', 'created_at', 'vote', 'votes_result'
        ])
        ->and($response->json('data.vote'))->toBe('up_vote')
        ->and($response->json('data.votes_result'))->toBe(1);
});

test('replace upvote with downvote when downvote is clicked', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $post = Post::factory()->create();
    $post->upvoteToggle($user);

    $response = $this->postJson('/api/post/downvote', ['post_id' => $post->id]);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toHaveKeys([
            'id', 'content', 'created_at', 'vote', 'votes_result'
        ])
        ->and($response->json('data.vote'))->toBe('down_vote')
        ->and($response->json('data.votes_result'))->toBe(-1);
});


test('user can not upvote a post if not authenticated', function () {
    $post = Post::factory()->create();
    $response = $this->postJson('/api/post/upvote', ['post_id' => $post->id]);

    expect($response->getStatusCode())->toBe(401);
});

test('user can not downvote a post if not authenticated', function () {
    $post = Post::factory()->create();
    $response = $this->postJson('/api/post/downvote', ['post_id' => $post->id]);

    expect($response->getStatusCode())->toBe(401);
});

test('user can not upvote a post if post does not exist', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->postJson('/api/post/upvote', ['post_id' => 1]);

    expect($response->getStatusCode())->toBe(422)
        ->and($response->json())->toHaveKey('message')
        ->and($response->json('message'))->toBe('The selected post id is invalid.');
});

test('user can not downvote a post if post does not exist', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->postJson('/api/post/downvote', ['post_id' => 1]);

    expect($response->getStatusCode())->toBe(422)
        ->and($response->json())->toHaveKey('message')
        ->and($response->json('message'))->toBe('The selected post id is invalid.');
});

test('user can upvote a comment', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $comment = Comment::factory()->create();
    $response = $this->postJson('/api/comments/upvote', ['comment_id' => $comment->id]);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toHaveKeys([
            'id', 'title', 'created_at', 'vote', 'votes_result'
        ])
        ->and($response->json('data.vote'))->toBe('up_vote')
        ->and($response->json('data.votes_result'))->toBe(1);
});

test('user can downvote a comment', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $comment = Comment::factory()->create();
    $response = $this->postJson('/api/comments/downvote', ['comment_id' => $comment->id]);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toHaveKeys([
            'id', 'title', 'created_at', 'vote', 'votes_result'
        ])
        ->and($response->json('data.vote'))->toBe('down_vote')
        ->and($response->json('data.votes_result'))->toBe(-1);
});

test('user can undo upvote a comment', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $comment = Comment::factory()->create();
    $comment->upvoteToggle($user);
    $response = $this->postJson('/api/comments/upvote', ['comment_id' => $comment->id]);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toHaveKeys([
            'id', 'title', 'created_at', 'vote', 'votes_result'
        ])
        ->and($response->json('data.vote'))->toBe('no_vote')
        ->and($response->json('data.votes_result'))->toBe(0);
});

test('user can undo downvote a comment', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $comment = Comment::factory()->create();
    $comment->downvoteToggle($user);

    $response = $this->postJson('/api/comments/downvote', ['comment_id' => $comment->id]);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toHaveKeys([
            'id', 'title', 'created_at', 'vote', 'votes_result'
        ])
        ->and($response->json('data.vote'))->toBe('no_vote')
        ->and($response->json('data.votes_result'))->toBe(0);
});

test('replace downvote with upvote when upvote is clicked on comment', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $comment = Comment::factory()->create();
    $comment->downvoteToggle($user);

    $response = $this->postJson('/api/comments/upvote', ['comment_id' => $comment->id]);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toHaveKeys([
            'id', 'title', 'created_at', 'vote', 'votes_result'
        ])
        ->and($response->json('data.vote'))->toBe('up_vote')
        ->and($response->json('data.votes_result'))->toBe(1);
});

test('replace upvote with downvote when downvote is clicked on comment', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $comment = Comment::factory()->create();
    $comment->upvoteToggle($user);

    $response = $this->postJson('/api/comments/downvote', ['comment_id' => $comment->id]);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toHaveKeys([
            'id', 'title', 'created_at', 'vote', 'votes_result'
        ])
        ->and($response->json('data.vote'))->toBe('down_vote')
        ->and($response->json('data.votes_result'))->toBe(-1);
});

test('user can not upvote a comment if not authenticated', function () {
    $comment = Comment::factory()->create();
    $response = $this->postJson('/api/comments/upvote', ['comment_id' => $comment->id]);

    expect($response->getStatusCode())->toBe(401);
});

test('user can not downvote a comment if not authenticated', function () {
    $comment = Comment::factory()->create();
    $response = $this->postJson('/api/comments/downvote', ['comment_id' => $comment->id]);

    expect($response->getStatusCode())->toBe(401);
});

test('user can not upvote a comment if comment does not exist', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->postJson('/api/comments/upvote', ['comment_id' => 1]);

    expect($response->getStatusCode())->toBe(422)
        ->and($response->json())->toHaveKey('message')
        ->and($response->json('message'))->toBe('The selected comment id is invalid.');
});

test('user can not downvote a comment if comment does not exist', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->postJson('/api/comments/downvote', ['comment_id' => 1]);

    expect($response->getStatusCode())->toBe(422)
        ->and($response->json())->toHaveKey('message')
        ->and($response->json('message'))->toBe('The selected comment id is invalid.');
});

test('ensure that votable_type is App/Models/Post when post is upvoted and type is upvote', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user);
    $this->postJson('/api/post/upvote', ['post_id' => $post->id]);
    $query = \App\Models\Vote::where('votable_id', $post->id)->where('votable_type',
        'App\Models\Post');
    expect($query->exists())->toBeTrue();
    expect($query->first()->vote)->toBeTrue();
});

test('ensure that votable_type is App/Models/Post when post is downvoted', function () {
    $post = Post::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user);
    $this->postJson('/api/post/downvote', ['post_id' => $post->id]);
    $query = \App\Models\Vote::where('votable_id', $post->id)->where('votable_type',
        'App\Models\Post');
    expect($query->exists())->toBeTrue();
    expect($query->first()->vote)->toBeFalse();
});

test('ensure that votable_type is App/Models/Comment when comment is upvoted', function () {
    $comment = Comment::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user);
    $this->postJson('/api/comments/upvote', ['comment_id' => $comment->id]);
    $query = \App\Models\Vote::where('votable_id', $comment->id)->where('votable_type',
        'App\Models\Comment');
    expect($query->exists())->toBeTrue();
    dump($query->first()->vote);
    expect($query->first()->vote)->toBeTrue();
});

test('ensure that votable_type is App/Models/Comment when comment is downvoted', function () {
    $comment = Comment::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user);
    $this->postJson('/api/comments/downvote', ['comment_id' => $comment->id]);
    $query = \App\Models\Vote::where('votable_id', $comment->id)->where('votable_type',
        'App\Models\Comment');
    expect($query->exists())->toBeTrue();
    dump($query->first()->vote);
    expect($query->first()->vote)->toBeFalse();
});
