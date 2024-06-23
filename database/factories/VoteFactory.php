<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VoteFactory extends Factory
{
    protected $model = Vote::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'vote' => $this->faker->boolean(),
            'votable' => $this->faker->word(),

            'user_id' => User::factory(),
        ];
    }
}
