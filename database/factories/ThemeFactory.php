<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Theme>
 */
class ThemeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'logo' => $this->faker->imageUrl(640, 480, 'business'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
