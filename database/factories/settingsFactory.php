<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class settingsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'library_name' => 'Acadivio Library',
            'email' => 'contact@acadivio.com',
            'phone' => '1234567890',
            'address' => '123 Central St, Academic City',
            'logo' => null,
            'return_days' => 20,
            'fine' => 5
        ];
    }
}
