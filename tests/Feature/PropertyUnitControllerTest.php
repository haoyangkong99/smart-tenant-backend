<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class PropertyUnitControllerTest extends TestCase
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
        // Create some sample PropertyUnits
        PropertyUnit::factory()->count(3)->create();

        $response = $this->getJson('/api/auth/property-units', ['Authorization' => $this->token]);
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $property = Property::factory()->create();

        $data = [
            'name' => 'Unit 101',
            'room_num' => 101,
            'property_id' => $property->id,
        ];

        $response = $this->postJson('/api/auth/property-units', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'PropertyUnit' => [
                    'id',
                    'name',
                    'room_num',
                    'property_id',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function testShow()
    {
        $propertyUnit = PropertyUnit::factory()->create();

        $response = $this->getJson('/api/auth/property-units/' . $propertyUnit->id, ['Authorization' => $this->token]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $propertyUnit->id,
                'name' => $propertyUnit->name,
                'room_num' => $propertyUnit->room_num,
                'property_id' => $propertyUnit->property_id,
            ]);
    }

    public function testUpdate()
    {
        $propertyUnit = PropertyUnit::factory()->create();
        $propertyUnit->name='Updated Unit';


        $response = $this->putJson('/api/auth/property-units/' . $propertyUnit->id, $propertyUnit->toArray());

        $response->assertStatus(200);

        $updatedPropertyUnit = PropertyUnit::findOrFail($propertyUnit->id);
        $this->assertEquals($propertyUnit['name'], $updatedPropertyUnit->name);
    }

    public function testDestroy()
    {
        $propertyUnit = PropertyUnit::factory()->create();

        $response = $this->deleteJson('/api/auth/property-units/' . $propertyUnit->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('property_units', ['id' => $propertyUnit->id]);
    }
}