<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Electronics', 'Clothing', 'Books', 'Food', 'Sports'];
        
        return [
            'name' => fake()->words(3, true),
            'price' => fake()->numberBetween(100, 10000),
            'stock' => fake()->numberBetween(0, 100),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement($categories),
            'imageurl' => fake()->words(3,true)
        ];
    }
}
