<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(5),
            'body' => fake()->paragraph(2, true),
            'published' => fake()->boolean(60),
        ];
    }
}
