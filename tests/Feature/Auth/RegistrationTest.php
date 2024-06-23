<?php

use \Illuminate\Http\UploadedFile;

test('new users can register', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone_number' => '1234567890',
        'image' => UploadedFile::fake()->image('avatar.jpg')
    ]);

    expect($response->getStatusCode())->toBe(201)
        ->and($response->json('data'))->toHaveKeys(['id', 'name', 'phone_number', 'image']);
});
