<?php

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
            'id', 'content', 'created_at', 'vote', 'votes_result'])
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
            'id', 'content', 'created_at', 'vote', 'votes_result'])
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
            'id', 'content', 'created_at', 'vote', 'votes_result'])
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
            'id', 'content', 'created_at', 'vote', 'votes_result'])
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
            'id', 'content', 'created_at', 'vote', 'votes_result'])
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
            'id', 'content', 'created_at', 'vote', 'votes_result'])
        ->and($response->json('data.vote'))->toBe('down_vote')
        ->and($response->json('data.votes_result'))->toBe(-1);
});


test('user can not upvote a post if not authenticated', function () {
    $post = Post::factory()->create();
    $response = $this->postJson('/api/post/upvote', ['post_id' => $post->id]);

    expect($response->getStatusCode())->toBe(401);
});
