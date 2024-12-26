<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Maintainer;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Tests\Feature\ControllerTest;
class MaintainerControllerTest extends TestCase
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
        // Create sample maintainers with associated users and properties
        Maintainer::factory()->count(3)->create();

        $response = $this->getJson('/api/auth/maintainers', ['Authorization' => $this->token]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [

                ],
            ]);

        // Test when no maintainers exist
        Maintainer::truncate();
        $response = $this->getJson('/api/auth/maintainers', ['Authorization' => $this->token]);
        $response->assertStatus(404);
    }

    public function testStore()
    {
        $user = User::factory()->create();
        $property = Property::factory()->create();

        $data = [
            'user_id' => $user->id,
            'property_id' => $property->id,
            'maintenance_type' => 'Plumbing',
            'description' => 'Leaky faucet',
            'additional_info' => 'Urgent',
        ];

        $response = $this->postJson('/api/auth/maintainers', $data, ['Authorization' => $this->token]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'user_id',
                    'property_id',
                    'maintenance_type',
                    'description',
                    'additional_info',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function testShow()
    {
        $maintainer = Maintainer::factory()->create();

        $response = $this->getJson('/api/auth/maintainers/' . $maintainer->id, ['Authorization' => $this->token]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user_id',
                    'property_id',
                    'maintenance_type',
                    'description',
                    'additional_info',
                    'created_at',
                    'updated_at',
                ],
            ]);

        // Test when maintainer not found
        $response = $this->getJson('/api/auth/maintainers/' . 9999, ['Authorization' => $this->token]);
        $response->assertStatus(404);
    }

    public function testUpdate()
    {
        $maintainer = Maintainer::factory()->create();

        $maintainer->maintenance_type='Electrical';
        $maintainer->description='Faulty wiring';

        $response = $this->putJson('/api/auth/maintainers/' . $maintainer->id, $maintainer->toArray(), ['Authorization' => $this->token]);

        $response->assertStatus(200);

        $updatedMaintainer = Maintainer::findOrFail($maintainer->id);
        $this->assertEquals($maintainer['maintenance_type'], $updatedMaintainer->maintenance_type);
        $this->assertEquals($maintainer['description'], $updatedMaintainer->description);
    }

    public function testDelete()
    {
        $maintainer = Maintainer::factory()->create();

        $response = $this->deleteJson('/api/auth/maintainers/' . $maintainer->id, ['Authorization' => $this->token]);

        $response->assertStatus(200);
    }
}