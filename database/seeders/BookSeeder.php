<?php

namespace Database\Seeders;

use App\Models\book;
use App\Models\category;
use App\Models\author;
use App\Models\publisher;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (book::count() == 0) {
            // Get references
            $categories = category::all();
            $authors = author::all();
            $publishers = publisher::all();

            // Setup fallbacks if empty
            if ($authors->isEmpty()) {
                author::factory(10)->create();
                $authors = author::all();
            }
            if ($publishers->isEmpty()) {
                publisher::factory(10)->create();
                $publishers = publisher::all();
            }

            // Real books database
            $booksList = [
                // Programming (ID 1) -> Book
                ['name' => 'Clean Code: Handbook of Software Craftsmanship', 'cat_index' => 0, 'type' => 'Book', 'qty' => 10],
                ['name' => 'Introduction to Algorithms', 'cat_index' => 0, 'type' => 'Book', 'qty' => 5],
                ['name' => 'Design Patterns: Elements of Reusable Object-Oriented Software', 'cat_index' => 0, 'type' => 'Book', 'qty' => 8],
                
                // Business (ID 2) -> Book
                ['name' => 'The Lean Startup', 'cat_index' => 1, 'type' => 'Book', 'qty' => 7],
                ['name' => 'Zero to One: Notes on Startups', 'cat_index' => 1, 'type' => 'Book', 'qty' => 6],
                
                // Mythological (ID 3) -> Story
                ['name' => 'Mahabharat', 'cat_index' => 2, 'type' => 'Story', 'qty' => 12],
                ['name' => 'Ramayana', 'cat_index' => 2, 'type' => 'Story', 'qty' => 10],
                
                // Sad Poem (ID 4) -> Poem
                ['name' => 'Aansoo (Tears of Sorrow)', 'cat_index' => 3, 'type' => 'Poem', 'qty' => 3],
                ['name' => 'Madhushala', 'cat_index' => 3, 'type' => 'Poem', 'qty' => 4],
                
                // Nature Poem (ID 5) -> Poem
                ['name' => 'Birches', 'cat_index' => 4, 'type' => 'Poem', 'qty' => 5],
                ['name' => 'Stopping by Woods on a Snowy Evening', 'cat_index' => 4, 'type' => 'Poem', 'qty' => 4],
                
                // Management Research (ID 6) -> Research Paper
                ['name' => 'Strategic Corporate Planning in 2026', 'cat_index' => 5, 'type' => 'Research Paper', 'qty' => 2],
                
                // Technology Research (ID 7) -> Research Paper
                ['name' => 'The Role of Neural Networks in Compiler Optimization', 'cat_index' => 6, 'type' => 'Research Paper', 'qty' => 3],
                
                // Science Research (ID 8) -> Research Paper
                ['name' => 'Quantum Mechanics & Physical Realities', 'cat_index' => 7, 'type' => 'Research Paper', 'qty' => 2],
                
                // Fiction (ID 9) -> Story
                ['name' => 'Harry Potter and the Sorcerer\'s Stone', 'cat_index' => 8, 'type' => 'Story', 'qty' => 15],
                ['name' => 'The Hobbit', 'cat_index' => 8, 'type' => 'Story', 'qty' => 9],
                
                // Biography (ID 10) -> Book
                ['name' => 'Steve Jobs: The Exclusive Biography', 'cat_index' => 9, 'type' => 'Book', 'qty' => 6],
                ['name' => 'Wings of Fire: APJ Abdul Kalam', 'cat_index' => 9, 'type' => 'Book', 'qty' => 8]
            ];

            foreach ($booksList as $b) {
                // Find matching category by index or fallback
                $catId = isset($categories[$b['cat_index']]) ? $categories[$b['cat_index']]->id : $categories->first()->id;
                
                book::create([
                    'name' => $b['name'],
                    'category_id' => $catId,
                    'author_id' => $authors->random()->id,
                    'publisher_id' => $publishers->random()->id,
                    'quantity' => $b['qty'],
                    'type' => $b['type'],
                    'status' => 'Y'
                ]);
            }
        }
    }
}
