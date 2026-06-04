<?php

namespace Tests\Feature;

use App\Models\publisher;
use App\Models\book;
use App\Models\User;
use App\Models\category;
use App\Models\author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublisherCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_publisher_index_shows_publisher_books_modal_content()
    {
        $user = User::factory()->create();

        // Create references
        $category = category::create(['name' => 'General Science']);
        $author = author::create(['name' => 'Premchand']);
        
        // Create publisher
        $publisher = publisher::create(['name' => 'Vikas Publishing']);

        // Create books for this publisher
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

        // Visit publisher list page
        $response = $this->actingAs($user)->get(route('publishers'));

        $response->assertStatus(200);

        // Check if publisher name exists
        $response->assertSee('Vikas Publishing');

        // Check if types exist in the modal structure
        $response->assertSee('पुस्तक (Book)');
        $response->assertSee('कहानी (Story)');

        // Check if specific books are displayed
        $response->assertSee('Godan');
        $response->assertSee('Qty: 10');
        $response->assertSee('Kafan');
        $response->assertSee('Qty: 5');
    }
}
