<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\VoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('posts', PostController::class);
    Route::post('post/upvote', [VoteController::class, 'upvoteTogglePost']);
    Route::post('post/downvote', [VoteController::class, 'downvoteTogglePost']);

});
