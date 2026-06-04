<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class studentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $gender=['male','female'];
        return [
            'name' => $this->faker->name,
            'age' => random_int(18,80),
            'gender' => $gender[random_int(0,1)],
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'class' => $this->faker->randomElement(['B.Tech 1st Year', 'B.Tech 2nd Year', 'BCA 3rd Year', 'MCA 1st Year']),
            'branch' => $this->faker->randomElement(['Computer Science', 'Information Technology', 'Mechanical', 'Civil']),
            'category' => $this->faker->randomElement(['General', 'OBC', 'SC', 'ST']),
            'photo' => null
        ];
    }
}
