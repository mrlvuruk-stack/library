<?php

namespace Database\Seeders;

use App\Models\author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (author::count() == 0) {
            author::factory(10)->create();
        }
    }
}
