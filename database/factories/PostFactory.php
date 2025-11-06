<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        // 70% de chance que le post soit publié, 30% qu'il soit un brouillon
        $isPublished = fake()->boolean(70);
        
        return [
            'title' => fake()->sentence(rand(3, 8), true),
            'content' => implode("\n\n", fake()->paragraphs(rand(3, 8))),
            'user_id' => User::factory(), // Sera remplacé si fourni lors de la création
            'published_at' => $isPublished ? fake()->dateTimeBetween('-1 year', 'now') : null,
        ];
    }
}
