<?php

namespace Tests\Feature;

use App\Models\author;
use App\Models\book;
use App\Models\category;
use App\Models\publisher;
use App\Models\settings;
use App\Models\student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CsvImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_update_library_profile_details_and_logo()
    {
        $user = User::factory()->create();
        $setting = settings::factory()->create([
            'library_name' => 'Original Library',
            'logo' => null
        ]);

        $logoBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $response = $this->actingAs($user)->post(route('settings'), [
            'library_name' => 'Acadivio Central Library',
            'email' => 'admin@acadivio.com',
            'phone' => '9999999999',
            'address' => '456 Knowledge Boulevard',
            'return_days' => 15,
            'fine' => 10,
            'logo' => $logoBase64
        ]);

        $response->assertRedirect(route('settings'));
        
        $setting->refresh();
        $this->assertEquals('Acadivio Central Library', $setting->library_name);
        $this->assertEquals('admin@acadivio.com', $setting->email);
        $this->assertEquals('9999999999', $setting->phone);
        $this->assertEquals(15, $setting->return_days);
        $this->assertEquals(10, $setting->fine);
        $this->assertNotNull($setting->logo);

        // Cleanup
        $filePath = public_path('/images/' . $setting->logo);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function test_download_student_csv_sample_template()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('student.import.sample'));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename=students_import_template.csv');
        $response->assertSee('Name,Age,Gender,Email,Phone,Address,Class,Branch,Category');
    }

    public function test_import_students_csv_file()
    {
        $user = User::factory()->create();

        // Create CSV file content
        $csvContent = "Name,Age,Gender,Email,Phone,Address,Class,Branch,Category\n";
        $csvContent .= "Test Student One,18,male,one@example.com,9876543210,Address One,12th Class,Maths,General\n";
        $csvContent .= "Test Student Two,17,female,two@example.com,9876543211,Address Two,12th Class,Science,OBC\n";

        $tempFile = UploadedFile::fake()->createWithContent('students.csv', $csvContent);

        $response = $this->actingAs($user)->post(route('student.import'), [
            'csv_file' => $tempFile
        ]);

        $response->assertRedirect(route('students'));

        // Verify students are in the database
        $this->assertDatabaseHas('students', [
            'name' => 'Test Student One',
            'email' => 'one@example.com',
            'branch' => 'Maths',
            'category' => 'General'
        ]);

        $this->assertDatabaseHas('students', [
            'name' => 'Test Student Two',
            'email' => 'two@example.com',
            'branch' => 'Science',
            'category' => 'OBC'
        ]);

        $this->assertEquals(2, student::count());
    }

    public function test_download_book_csv_sample_template()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('book.import.sample'));
        
        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename=books_import_template.csv');
        $response->assertSee('Title,Author,Publisher,Category,Quantity,Type');
    }

    public function test_import_books_csv_file_creates_relationships_on_the_fly()
    {
        $user = User::factory()->create();

        // Ensure database is clean of relationships first
        $this->assertEquals(0, author::count());
        $this->assertEquals(0, publisher::count());
        $this->assertEquals(0, category::count());
        $this->assertEquals(0, book::count());

        // Create CSV file content
        $csvContent = "Title,Author,Publisher,Category,Quantity,Type\n";
        $csvContent .= "Design Patterns,GoF,Addison-Wesley,Software Engineering,3,Book\n";
        $csvContent .= "Sherlock Holmes,Arthur Conan Doyle,George Newnes,Detective Fiction,5,Book\n";

        $tempFile = UploadedFile::fake()->createWithContent('books.csv', $csvContent);

        $response = $this->actingAs($user)->post(route('book.import'), [
            'csv_file' => $tempFile
        ]);

        $response->assertRedirect(route('books'));

        // Verify relationships are created on the fly
        $this->assertDatabaseHas('authors', ['name' => 'GoF']);
        $this->assertDatabaseHas('authors', ['name' => 'Arthur Conan Doyle']);
        $this->assertDatabaseHas('publishers', ['name' => 'Addison-Wesley']);
        $this->assertDatabaseHas('publishers', ['name' => 'George Newnes']);
        $this->assertDatabaseHas('categories', ['name' => 'Software Engineering']);
        $this->assertDatabaseHas('categories', ['name' => 'Detective Fiction']);

        // Verify books are in database
        $this->assertDatabaseHas('books', [
            'name' => 'Design Patterns',
            'quantity' => 3,
            'type' => 'Book'
        ]);

        $this->assertDatabaseHas('books', [
            'name' => 'Sherlock Holmes',
            'quantity' => 5,
            'type' => 'Book'
        ]);

        $this->assertEquals(2, book::count());
    }
}
