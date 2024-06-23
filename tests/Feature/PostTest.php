<?php

use App\Models\Post;
use App\Models\User;

test('user can view all posts', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    Post::factory()->count(5)->create();
    $response = $this->getJson('/api/posts');

    expect($response->getStatusCode())->toBe(200)
    ->and($response->json())->toHaveKey('data')
    ->and($response->json('data'))->toHaveCount(5)
    ->and($response->json('data.0'))->toHaveKeys(['id', 'content', 'user', 'created_at'])
    ->and($response->json('data.0.user'))->toHaveKeys(['id', 'name']);
});

test('user gets empty data if there is no posts', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->getJson('/api/posts');

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toHaveCount(0);
});

test('user can view a post', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $post = Post::factory()->create();
    $response = $this->getJson("/api/posts/{$post->id}");

    expect($response->getStatusCode())->toBe(200)
    ->and($response->json('data'))->toHaveKeys(['id', 'content', 'user', 'created_at'])
    ->and($response->json('data.user'))->toHaveKeys(['id', 'name']);
});

test('user gets 404 when trying to get a not found post', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->getJson("/api/posts/123");
    $response->dump();
    expect($response->getStatusCode())->toBe(404)
        ->and($response->json())->toHaveKey('message');
});

test('user can create a post', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $input = [
        'content' => 'Hello World',
    ];
    $response = $this->postJson('/api/posts', $input);

    expect($response->getStatusCode())->toBe(201)
    ->and($response->json('data'))->toHaveKeys(['id', 'content', 'user', 'created_at'])
    ->and($response->json('data.user'))->toHaveKeys(['id', 'name']);
});

test('unauthenticated user gets 401 when trying to create a post', function () {
    $input = [
        'content' => 'Hello World',
    ];
    $response = $this->postJson('/api/posts', $input);

    expect($response->getStatusCode())->toBe(401)
    ->and($response->json())->toHaveKey('message');
});

test('user can update a post', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $post = Post::factory()->create(['user_id' => $user->id]);
    $input = [
        'content' => 'Hello World',
    ];
    $response = $this->putJson("/api/posts/{$post->id}", $input);

    expect($response->getStatusCode())->toBe(200)
    ->and($response->json('data'))->toHaveKeys(['id', 'content', 'user', 'created_at'])
    ->and($response->json('data.user'))->toHaveKeys(['id', 'name']);
});

test('user gets 403 when trying to update a post that does not belong to him', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $post = Post::factory()->create();
    $input = [
        'content' => 'Hello World',
    ];
    $response = $this->putJson("/api/posts/{$post->id}", $input);

    expect($response->getStatusCode())->toBe(403)
    ->and($response->json())->toHaveKey('message');
});

test('user can delete a post', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $post = Post::factory()->create(['user_id' => $user->id]);

    $response = $this->deleteJson("/api/posts/{$post->id}");

    expect($response->getStatusCode())->toBe(204);
});

test('user gets 403 when trying to delete a post that does not belong to him', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $post = Post::factory()->create();

    $response = $this->deleteJson("/api/posts/{$post->id}");

    expect($response->getStatusCode())->toBe(403)
    ->and($response->json())->toHaveKey('message');
});
