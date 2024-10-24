<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Comment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Answer>
 */
class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'comment_id' => Comment::all()->random(),
            'user_id' => User::factory(),
            'content' => $this->faker->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
            'receiver_id' => User::all()->random(),
        ];
    }
}
