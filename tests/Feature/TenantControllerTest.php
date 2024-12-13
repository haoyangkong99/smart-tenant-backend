<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Factories\TenantFactory;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TenantControllerTest extends TestCase
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
        $response = $this->getJson('/api/auth/tenants');

        $response->assertStatus(200);

        // Test when no tenants exist
        Tenant::truncate();
        $response = $this->getJson('/api/auth/tenants');
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $data = [
            'user_id' => User::all()->random()->id,
            'total_family_member' => 4,
        ];

        $response = $this->postJson('/api/auth/tenants', $data, ['Authorization' => $this->token]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'tenant' => [
                        'id',
                        'user_id',
                        'total_family_member',
                        'created_at',
                        'updated_at',
                    ],
                ]);
    }

    public function testShow()
    {
        // Create a tenant using factory
        $tenant = Tenant::factory()->create();

        $response = $this->getJson('/api/auth/tenants/' . $tenant->id);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'user_id',
                    'total_family_member',
                    'created_at',
                    'updated_at',
                    'user' => [
                        // User model fields (e.g., id, name, email)
                    ],
                ]);

        // Test when tenant not found
        $response = $this->getJson('/api/auth/tenants/' . 9999); // Non-existent ID
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $tenant = Tenant::factory()->create();

        $data = [
            'user_id' => User::all()->random()->id,
            'total_family_member' => 5,
        ];

        $response = $this->putJson('/api/auth/tenants/' . $tenant->id, $data, ['Authorization' => $this->token]);

        $response->assertStatus(200)
                ->assertJsonFragment($data);
    }

    public function testDelete()
    {
        $tenant = Tenant::factory()->create();

        $response = $this->deleteJson('/api/auth/tenants/' . $tenant->id, ['Authorization' => $this->token]);

        $response->assertStatus(200);
    }
}