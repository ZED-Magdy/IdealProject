<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $comments = [
            [
                'title' => 'My first comment',
            ],
            [
                'title' => 'My second comment',
            ],
            [
                'title' => 'My third comment',
            ],
            [
                'title' => 'My fourth comment',
            ],
            [
                'title' => 'My fifth comment',
            ]
        ];

        foreach ($comments as $comment) {
            Comment::factory()->create($comment);
        }

    }
}
