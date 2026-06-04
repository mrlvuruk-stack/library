<?php

namespace Tests\Feature;

use App\Models\author;
use App\Models\book;
use App\Models\User;
use App\Models\category;
use App\Models\publisher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorWorksTest extends TestCase
{
    use RefreshDatabase;

    public function test_author_index_shows_author_works_modal_content()
    {
        // Create user
        $user = User::factory()->create();

        // Create references
        $category = category::create(['name' => 'General Science']);
        $publisher = publisher::create(['name' => 'Standard Publishing']);

        // Create author
        $author = author::create(['name' => 'Premchand']);

        // Create books of different types for this author
        book::create([
            'name' => 'Godan',
            'category_id' => $category->id,
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'quantity' => 10,
            'type' => 'Book',
            'status' => 'Y'
        ]);

        book::create([
            'name' => 'Kafan',
            'category_id' => $category->id,
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'quantity' => 5,
            'type' => 'Story',
            'status' => 'Y'
        ]);

        book::create([
            'name' => 'Self-Publishing Guide',
            'category_id' => $category->id,
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'quantity' => 2,
            'type' => 'Blog',
            'status' => 'Y'
        ]);

        // Access index page as the user
        $response = $this->actingAs($user)->get(route('authors'));

        $response->assertStatus(200);

        // Check if author name exists
        $response->assertSee('Premchand');

        // Check if types (Hindi labels) exist in the modal structure
        $response->assertSee('पुस्तक (Book)');
        $response->assertSee('कहानी (Story)');
        $response->assertSee('कविता (Poem)');
        $response->assertSee('ब्लॉग (Blog)');
        $response->assertSee('लेख (Article)');
        $response->assertSee('शोध-पत्र (Research Paper)');

        // Check if specific books are displayed
        $response->assertSee('Godan');
        $response->assertSee('Qty: 10');
        $response->assertSee('Kafan');
        $response->assertSee('Qty: 5');
        $response->assertSee('Self-Publishing Guide');
        $response->assertSee('Qty: 2');
    }
}
