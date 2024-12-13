<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PropertyControllerTest extends TestCase
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
        // Create sample properties using factory
        Property::factory()->count(3)->create();

        $response = $this->getJson('/api/auth/properties', ['Authorization' => $this->token]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'type',
                        'name',
                        'description',
                        'address',
                        'country',
                        'state',
                        'city',
                        'post_code',
                        'image',
                        'created_at',
                        'updated_at',
                    ],
                ]);

        // Test when no properties exist
        Property::truncate();
        $response = $this->getJson('/api/auth/properties', ['Authorization' => $this->token]);
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $data = [
            'type' => 'House',
            'name' => 'Test Property',
            'description'=>'Test Description',
            'address' => '123 Main St',
            'country' => 'USA',
            'state'=>'California',
            'city'=>'Orlando',
            'post_code'=>'123456'

        ];

        $response = $this->postJson('/api/auth/properties', $data, ['Authorization' => $this->token]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'property' => [
                        'id',
                        'type',
                        'name',
                        'description',
                        'address',
                        'country',
                        'state',
                        'city',
                        'post_code',
                        'created_at',
                        'updated_at',
                    ],
                ]);
    }

    public function testShow()
    {
        $property = Property::factory()->create();

        $response = $this->getJson('/api/auth/properties/' . $property->id, ['Authorization' => $this->token]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'type',
                    'name',
                    'description',
                    'address',
                    'country',
                    'state',
                    'city',
                    'post_code',
                    'image',
                    'created_at',
                    'updated_at',
                ]);

        // Test when property not found
        $response = $this->getJson('/api/auth/properties/' . 9999, ['Authorization' => $this->token]);
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $property = Property::factory()->create();
        $property->name='Updated Property';

        $response = $this->putJson('/api/auth/properties/' . $property->id, $property->toArray(), ['Authorization' => $this->token]);
        $response->assertStatus(200);
        $updatedProperty = Property::findOrFail($property->id);

        // Assert specific fields of the updated property
        $this->assertEquals($property['name'], $updatedProperty->name);
    }

    public function testDelete()
    {
        $property = Property::factory()->create();

        $response = $this->deleteJson('/api/auth/properties/' . $property->id, ['Authorization' => $this->token]);

        $response->assertStatus(200);
    }
}