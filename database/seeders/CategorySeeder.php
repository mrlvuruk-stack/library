<?php

namespace Database\Seeders;

use App\Models\category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (category::count() == 0) {
            $categories = [
                ['name' => 'Programming (प्रोग्रामिंग)'],
                ['name' => 'Business (व्यवसाय)'],
                ['name' => 'Mythological (पौराणिक)'],
                ['name' => 'Sad Poem (दर्दभरी कविता)'],
                ['name' => 'Nature Poem (प्रकृति कविता)'],
                ['name' => 'Management Research (प्रबंधन शोध)'],
                ['name' => 'Technology Research (तकनीकी शोध)'],
                ['name' => 'Science Research (विज्ञान शोध)'],
                ['name' => 'Fiction (कल्पना)'],
                ['name' => 'Biography (जीवनी)'],
            ];

            foreach ($categories as $cat) {
                category::create($cat);
            }
        }
    }
}
