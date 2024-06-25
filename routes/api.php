<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VoteController;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('verify-otp-code', [AuthController::class, 'verify']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('posts', PostController::class);
    Route::post('post/upvote', [VoteController::class, 'upvoteTogglePost']);
    Route::post('post/downvote', [VoteController::class, 'downvoteTogglePost']);
    Route::get('posts/{post}/comments', [CommentController::class, 'index']);
    Route::get('comments/{comment}', [CommentController::class, 'show']);
    Route::post('posts/{post}/comments', [CommentController::class, 'store']);
    Route::put('comments/{comment}', [CommentController::class, 'update']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
    Route::post('comments/upvote', [VoteController::class, 'upvoteToggleComment']);
    Route::post('comments/downvote', [VoteController::class, 'downvoteToggleComment']);

});
