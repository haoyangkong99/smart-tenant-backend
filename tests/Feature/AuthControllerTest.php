<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Factories\UserFactory;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Auth;
use Tests\Feature\ControllerTest;
class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    private $token='';
    private $user;
    public function setUp(): void
{
    parent::setUp();

    $this->seed(DatabaseSeeder::class);
    $this->user =  User::all()->first();
    $token = Auth::guard('api')->login($this->user);
    $this->token='Bearer '.$token;
}
    public function testRegister()
    {

        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'phone_number' => '0123456789',
        ];

        $response = $this->postJson('/api/auth/register', $data,['Authorization'=>$this->token]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'phone_number',
                    'created_at',
                    'updated_at',
                ]
            ]);
            $dataChecking = [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'phone_number' => '0123456789',
            ];
        $this->assertDatabaseHas('users', $dataChecking);
    }

    public function testLogin()
    {
        $credentials = [
            'email' => $this->user->email,
            'password' => 'admin'
        ];
        echo $credentials['email'];
        $response = $this->postJson('/api/login', $credentials);

        $response->assertStatus(200);
    }

    public function testLogout()
    {
        // Assuming authentication is set up
        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(200);
        // You might need to add assertions to verify logout status, depending on your authentication setup
    }

    public function testUser()
    {
        // Assuming authentication is set up
        $response = $this->getJson('/api/auth/user',['Authorization'=>$this->token]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    // ... other user fields
                ]
            ]);
    }

    public function testUpdateUser()
    {


        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'phone_number' => '0987654321',
        ];

        $response = $this->putJson('/api/auth/user/' . $this->user->id, $updateData,['Authorization'=>$this->token]);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('users', $updateData);
    }

    public function testChangePassword()
    {


        $updateData = [
            'password' => 'newpassword',
        ];

        $response = $this->putJson('/api/auth/user/' . $this->user->id . '/change-password', $updateData,['Authorization'=>$this->token]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', ['id' => $this->user->id]); // Ensure user still exists after password change

        // You might want to add more specific assertions to verify password change
    }
}