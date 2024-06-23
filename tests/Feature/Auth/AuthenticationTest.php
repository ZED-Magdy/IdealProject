<?php

use App\Models\User;
use \Illuminate\Http\UploadedFile;

test('users can authenticate using the login screen', function () {
    $user = User::factory(['phone_number' => '1234567890'])->create();

    $response = $this->postJson('/api/login', [
        'phone_number' => $user->phone_number,
    ]);

    expect($response->getStatusCode())->toBe(200);

    $response->assertJson(['message' => __('OTP sent successfully')]);

});

test('user login not found', function () {

    $response = $this->postJson('/api/login', [
        'phone_number' => '01258963217',
    ]);

    $response->dump();

    expect($response->getStatusCode())->toBe(500);

    $response->assertJson([
        'message' => 'User not found',
    ]);
});

test('login validation error', function () {

    $response = $this->postJson('/api/login', [
        'phone_number' => '',
    ]);

    expect($response->getStatusCode())->toBe(422);

    $response->assertJsonValidationErrors(['phone_number']);
});

test('verify invalid code', function () {

    $user = User::factory()->create([
        'phone_number' => '1234567890',
        'otp_code' => Hash::make('123456')
    ]);

    $response = $this->postJson('/api/verify-otp-code', [
        'phone_number' => '1234567890',
        'otp_code' => '654321',
    ]);

    expect($response->getStatusCode())->toBe(500);

    $response->assertJson([
        "message" => "Invalid code"
    ]);
});

test('verify user not found', function () {

    $response = $this->postJson('/api/verify-otp-code', [
        'phone_number' => '0987654321',
        'otp_code' => '123456',
    ]);

    expect($response->getStatusCode())->toBe(500);
    $response->assertJson([
            'message' => 'user not found',
        ]);
});



