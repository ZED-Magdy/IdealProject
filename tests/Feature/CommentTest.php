<?php
/**
 * @var \Tests\TestCase $this
 */

test('as user i can add a comment', function () {
    $post = \App\Models\Post::factory()->create();
    $user = \App\Models\User::factory()->create();

    $this->actingAs($user);

    $input = [
        'title' => 'Hello World',
    ];

    $response = $this->postJson("/api/posts/{$post->id}/comments", $input);

    expect($response->getStatusCode())->toBe(201)
        ->and($response->json('data'))->toHaveKeys(['id', 'title', 'user', 'created_at'])
        ->and($response->json('data.user'))->toHaveKeys(['id', 'name']);

});

test('as user i can view all comments of a post', function () {
    $post = \App\Models\Post::factory()->create();
    $user = \App\Models\User::factory()->create();

    $this->actingAs($user);

    \App\Models\Comment::factory()->count(5)->create([
        'post_id' => $post->id
    ]);

    $response = $this->getJson("/api/posts/{$post->id}/comments");

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('data')
        ->and($response->json('data'))->toHaveCount(5)
        ->and($response->json('data.0'))->toHaveKeys(['id', 'title', 'user', 'created_at'])
        ->and($response->json('data.0.user'))->toHaveKeys(['id', 'name']);
});

test('as user i can view a comment', function () {

    $post = \App\Models\Post::factory()->create();
    $user = \App\Models\User::factory()->create();

    $this->actingAs($user);

    $comment = \App\Models\Comment::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
        'title' => 'Hello World',
    ]);

    $comment->refresh();


    $response = $this->getJson("/api/comments/{$comment->id}");


    expect($response->getStatusCode())->toBe(200)
        ->and($response->json('data'))->toHaveKeys(['id', 'title', 'user', 'created_at'])
        ->and($response->json('data.user'))->toHaveKeys(['id', 'name']);
});

test('as user i can update a comment', function () {

    $post = \App\Models\Post::factory()->create();
    $user = \App\Models\User::factory()->create();

    $this->actingAs($user);

    $comment = \App\Models\Comment::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
        'title' => 'Hello World',
    ]);

    $comment->refresh();

    $input = [
        'title' => 'Hello World Updated',
    ];

    $response = $this->putJson("/api/comments/{$comment->id}", $input);


    expect($response->getStatusCode())->toBe(200)
        ->and($response->json('data'))->toHaveKeys(['id', 'title', 'user', 'created_at'])
        ->and($response->json('data.user'))->toHaveKeys(['id', 'name']);
});

test('as user i can delete a comment', function () {

    $post = \App\Models\Post::factory()->create();
    $user = \App\Models\User::factory()->create();

    $this->actingAs($user);

    $comment = \App\Models\Comment::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user->id,
        'title' => 'Hello World',
    ]);

    $comment->refresh();

    $response = $this->deleteJson("/api/comments/{$comment->id}");

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json())->toHaveKey('message');
});

test('as user i can not delete a comment that does not belong to me', function () {

    $post = \App\Models\Post::factory()->create();
    $user = \App\Models\User::factory()->create();
    $user2 = \App\Models\User::factory()->create();

    $this->actingAs($user);

    $comment = \App\Models\Comment::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user2->id,
        'title' => 'Hello World',
    ]);

    $comment->refresh();

    $response = $this->deleteJson("/api/comments/{$comment->id}");

    expect($response->getStatusCode())->toBe(403)
        ->and($response->json())->toHaveKey('message');
});

test('as user i can not update a comment that does not belong to me', function () {

    $post = \App\Models\Post::factory()->create();
    $user = \App\Models\User::factory()->create();
    $user2 = \App\Models\User::factory()->create();

    $this->actingAs($user);

    $comment = \App\Models\Comment::factory()->create([
        'post_id' => $post->id,
        'user_id' => $user2->id,
        'title' => 'Hello World',
    ]);

    $comment->refresh();

    $input = [
        'title' => 'Hello World Updated',
    ];

    $response = $this->putJson("/api/comments/{$comment->id}", $input);

    expect($response->getStatusCode())->toBe(403)
        ->and($response->json())->toHaveKey('message');
});

test('as i user i should not be able to add a comment to a post that does not exist', function () {

    $user = \App\Models\User::factory()->create();

    $this->actingAs($user);

    $input = [
        'title' => 'Hello World',
    ];

    $response = $this->postJson("/api/posts/1/comments", $input);

    expect($response->getStatusCode())->toBe(404)
        ->and($response->json())->toHaveKey('message');
});

test('as i user i should not be able to view comments of a post that does not exist', function () {

    $user = \App\Models\User::factory()->create();

    $post = \App\Models\Post::latest()->first();

    $this->actingAs($user);

    $response = $this->getJson("/api/posts/1/comments");

    expect($response->getStatusCode())->toBe(404)
        ->and($response->json())->toHaveKey('message');
});

test('as i user i should not be able to view a comment that does not exist', function () {

    $user = \App\Models\User::factory()->create();

    $this->actingAs($user);

    $response = $this->getJson("/api/comments/1");

    expect($response->getStatusCode())->toBe(404)
        ->and($response->json())->toHaveKey('message');
});
