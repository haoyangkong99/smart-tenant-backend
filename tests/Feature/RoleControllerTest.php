<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;
    private $token = '';

    public function setUp(): void
    {
        parent::setUp();

        $user = User::all()->first();
        $token = Auth::guard('api')->login($user);
        $this->token = 'Bearer ' . $token;
    }

    public function testIndex()
    {
        // Create sample roles
        Role::factory()->count(3)->create();

        $response = $this->getJson('/api/auth/roles', ['Authorization' => $this->token]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'title',
                        'created_at',
                        'updated_at',
                    ],
                ]);

        // Test when no roles exist
        Role::truncate();
        $response = $this->getJson('/api/auth/roles', ['Authorization' => $this->token]);
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $data = [
            'title' => 'Admin',
        ];

        $response = $this->postJson('/api/auth/roles', $data, ['Authorization' => $this->token]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'role' => [
                        'id',
                        'title',
                        'created_at',
                        'updated_at',
                    ],
                ]);
    }

    public function testShow()
    {
        $role = Role::factory()->create();

        $response = $this->getJson('/api/auth/roles/' . $role->id, ['Authorization' => $this->token]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'title',
                    'created_at',
                    'updated_at',
                ]);

        // Test when role not found
        $response = $this->getJson('/api/auth/roles/' . 9999, ['Authorization' => $this->token]);
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $role = Role::factory()->create();

        $data = [
            'title' => 'Updated Role',
        ];

        $response = $this->putJson('/api/auth/roles/' . $role->id, $data, ['Authorization' => $this->token]);

        $response->assertStatus(200)
                ->assertJsonFragment($data);
    }

    public function testDelete()
    {
        $role = Role::factory()->create();

        $response = $this->deleteJson('/api/auth/roles/' . $role->id, ['Authorization' => $this->token]);

        $response->assertStatus(200);
    }
}