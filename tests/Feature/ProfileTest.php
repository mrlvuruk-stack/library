<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_profile_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.view'));

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee('Librarian Profile');
    }

    public function test_user_can_update_profile_details_and_avatar()
    {
        $user = User::factory()->create();

        $avatar = UploadedFile::fake()->create('test_avatar.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('profile.update'), [
            'name' => 'Updated Name',
            'username' => 'updated_username',
            'email' => 'updated@example.com',
            'avatar' => $avatar,
        ]);

        $response->assertRedirect();
        
        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated_username', $user->username);
        $this->assertEquals('updated@example.com', $user->email);
        $this->assertNotNull($user->avatar);

        // Cleanup uploaded test image
        $uploadedPath = public_path('/images/' . $user->avatar);
        if (file_exists($uploadedPath)) {
            unlink($uploadedPath);
        }
    }
}
