<?php

namespace Tests\Feature;

use App\Models\book;
use App\Models\User;
use App\Models\category;
use App\Models\publisher;
use App\Models\author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_filter_books_by_type_and_subcategory()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();

        // Create references
        $categoryPoemSad = category::create(['name' => 'Sad Poem (दर्दभरी कविता)']);
        $categoryPoemNature = category::create(['name' => 'Nature Poem (प्रकृति कविता)']);
        $categoryProg = category::create(['name' => 'Programming (प्रोग्रामिंग)']);
        
        $publisher = publisher::create(['name' => 'Standard Publishing']);
        $author = author::create(['name' => 'Maharishi Vyas']);

        // Create books
        book::create([
            'name' => 'Aansoo',
            'category_id' => $categoryPoemSad->id,
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'quantity' => 10,
            'type' => 'Poem',
            'status' => 'Y'
        ]);

        book::create([
            'name' => 'Birches',
            'category_id' => $categoryPoemNature->id,
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'quantity' => 5,
            'type' => 'Poem',
            'status' => 'Y'
        ]);

        book::create([
            'name' => 'Clean Code',
            'category_id' => $categoryProg->id,
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'quantity' => 8,
            'type' => 'Book',
            'status' => 'Y'
        ]);

        // 1. Visit books route - should see all
        $response = $this->actingAs($user)->get(route('books'));
        $response->assertStatus(200);
        $response->assertSee('Aansoo');
        $response->assertSee('Birches');
        $response->assertSee('Clean Code');
        $response->assertSee('3'); // Total count is 3

        // 2. Filter by Type "Poem"
        $response = $this->actingAs($user)->get(route('books', ['type' => 'Poem']));
        $response->assertStatus(200);
        $response->assertSee('Aansoo');
        $response->assertSee('Birches');
        $response->assertDontSee('Clean Code');
        
        // Should show sub-category pills
        $response->assertSee('Sad Poem (दर्दभरी कविता)');
        $response->assertSee('Nature Poem (प्रकृति कविता)');

        // 3. Filter by Type "Poem" and Sub-category "Sad Poem"
        $response = $this->actingAs($user)->get(route('books', ['type' => 'Poem', 'category' => $categoryPoemSad->id]));
        $response->assertStatus(200);
        $response->assertSee('Aansoo');
        $response->assertDontSee('Birches');
        $response->assertDontSee('Clean Code');
    }
}
