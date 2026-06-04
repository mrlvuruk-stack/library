<?php

namespace Tests\Feature;

use App\Models\student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentProfileTest extends TestCase
{
    use RefreshDatabase;

    private $base64Photo = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

    public function test_authenticated_user_can_create_student_with_photo_and_category()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('student.store'), [
            'name' => 'John Doe',
            'address' => '123 Test St',
            'gender' => 'male',
            'class' => 'B.Tech 3rd Year',
            'branch' => 'Computer Science',
            'category' => 'OBC',
            'age' => 20,
            'phone' => '1234567890',
            'email' => 'johndoe@example.com',
            'photo' => $this->base64Photo
        ]);

        $response->assertRedirect(route('students'));
        
        $student = student::where('email', 'johndoe@example.com')->first();
        $this->assertNotNull($student);
        $this->assertEquals('Computer Science', $student->branch);
        $this->assertEquals('OBC', $student->category);
        $this->assertNotNull($student->photo);
        $this->assertStringContainsString('students/student_', $student->photo);

        // Cleanup
        $filePath = public_path('/images/' . $student->photo);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function test_authenticated_user_can_update_student_details_and_photo()
    {
        $user = User::factory()->create();
        $student = student::factory()->create([
            'name' => 'Old Name',
            'category' => 'General',
            'photo' => null
        ]);

        $response = $this->actingAs($user)->post(route('student.update', $student->id), [
            'name' => 'John Updated',
            'address' => 'New Address',
            'gender' => 'female',
            'class' => 'MCA 2nd Year',
            'branch' => 'Information Technology',
            'category' => 'SC',
            'age' => 22,
            'phone' => '0987654321',
            'email' => 'updated@example.com',
            'photo' => $this->base64Photo
        ]);

        $response->assertRedirect(route('students'));
        
        $student->refresh();
        $this->assertEquals('John Updated', $student->name);
        $this->assertEquals('SC', $student->category);
        $this->assertEquals('Information Technology', $student->branch);
        $this->assertNotNull($student->photo);

        // Cleanup
        $filePath = public_path('/images/' . $student->photo);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function test_student_show_endpoint_returns_correct_details()
    {
        $user = User::factory()->create();
        $student = student::factory()->create([
            'name' => 'Test Student Details',
            'category' => 'Scholarship',
            'branch' => 'Mechanical'
        ]);

        $response = $this->actingAs($user)->get(route('student.show', $student->id));
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Test Student Details',
            'category' => 'Scholarship',
            'branch' => 'Mechanical'
        ]);
    }

    public function test_student_list_filtering()
    {
        $user = User::factory()->create();

        // Create specific students to test filtering
        $studentA = student::factory()->create([
            'name' => 'Alice CS',
            'class' => 'Class1',
            'branch' => 'CS',
            'category' => 'General'
        ]);

        $studentB = student::factory()->create([
            'name' => 'Bob IT',
            'class' => 'Class2',
            'branch' => 'IT',
            'category' => 'OBC'
        ]);

        $studentC = student::factory()->create([
            'name' => 'Charlie CS',
            'class' => 'Class1',
            'branch' => 'CS',
            'category' => 'SC'
        ]);

        // 1. Filter by Class
        $response = $this->actingAs($user)->get(route('students', ['class' => 'Class1']));
        $response->assertSee('Alice CS');
        $response->assertSee('Charlie CS');
        $response->assertDontSee('Bob IT');

        // 2. Filter by Category
        $response = $this->actingAs($user)->get(route('students', ['category' => 'OBC']));
        $response->assertSee('Bob IT');
        $response->assertDontSee('Alice CS');
        $response->assertDontSee('Charlie CS');

        // 3. Filter by Branch
        $response = $this->actingAs($user)->get(route('students', ['branch' => 'CS']));
        $response->assertSee('Alice CS');
        $response->assertSee('Charlie CS');
        $response->assertDontSee('Bob IT');

        // 4. Search by keyword
        $response = $this->actingAs($user)->get(route('students', ['search' => 'Alice']));
        $response->assertSee('Alice CS');
        $response->assertDontSee('Bob IT');
        $response->assertDontSee('Charlie CS');
    }
}
