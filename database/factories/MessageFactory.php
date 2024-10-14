<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['was read', 'was unread']),
            'sender_id' => function () {
                return User::all()->random();
            },
            'receiver_id' => function () {
                return User::all()->random();
            },
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
