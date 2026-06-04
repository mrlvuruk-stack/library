<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class bookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(4),
            'category_id' => random_int(1,10),
            'author_id' => random_int(1,10),
            'publisher_id' => random_int(1,10),
            'quantity' => random_int(1,10),
            'type' => $this->faker->randomElement(['Book', 'Story', 'Poem', 'Blog', 'Article', 'Research Paper']),
            'status' => 'Y'
        ];
    }
}
