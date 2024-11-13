<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        return [
            'title' => $title,
            'body' => $this->faker->paragraph(),
            'summary' => Str::limit($this->faker->text(50), ),
            'slug' => Str::slug($title),
            'status' => $this->faker->randomElement(['published', 'draft', 'archived', 'pending']),
            'reading_time' => $this->faker->numberBetween(1, 20),
            'published_at' => random_int(0, 2)
                ? $this->faker->dateTimeBetween('-1 month', '+1 months')
                : null,
            'user_id' => User::factory(),
        ];
    }
}
