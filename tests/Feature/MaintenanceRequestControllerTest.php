<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Maintainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class MaintenanceRequestControllerTest extends TestCase
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
        // Create sample maintenance requests
        MaintenanceRequest::factory()->count(3)->create();

        $response = $this->getJson('/api/auth/maintenance-requests', ['Authorization' => $this->token]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'property_id',
                    'unit_id',
                    'maintainer_id',
                    'issue_type',
                    'status',
                    'issue_attachment',
                    'created_at',
                    'updated_at',
                    'property' => [
                        // Property model fields
                    ],
                    'unit' => [
                        // PropertyUnit model fields
                    ],
                    'maintainer' => [
                        // Maintainer model fields
                    ],
                ],
            ]);

        // Test when no maintenance requests exist
        MaintenanceRequest::truncate();
        $response = $this->getJson('/api/auth/maintenance-requests', ['Authorization' => $this->token]);
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $property = Property::factory()->create();
        $unit = PropertyUnit::factory()->create(['property_id' => $property->id]);
        $maintainer = Maintainer::factory()->create();

        $data = [
            'property_id' => $property->id,
            'unit_id' => $unit->id,
            'maintainer_id' => $maintainer->id,
            'issue_type' => 'Plumbing',
            'status' => 'PENDING',
            'issue_attachment' => null, // Or a valid file path
        ];

        $response = $this->postJson('/api/auth/maintenance-requests', $data, ['Authorization' => $this->token]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'maintenanceRequest' => [
                    'id',
                    'property_id',
                    'unit_id',
                    'maintainer_id',
                    'issue_type',
                    'status',
                    'issue_attachment',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function testShow()
    {
        $maintenanceRequest = MaintenanceRequest::factory()->create();

        $response = $this->getJson('/api/auth/maintenance-requests/' . $maintenanceRequest->id, ['Authorization' => $this->token]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'property_id',
                'unit_id',
                'maintainer_id',
                'issue_type',
                'status',
                'issue_attachment',
                'created_at',
                'updated_at',
                'property' => [
                    // Property model fields
                ],
                'unit' => [
                    // PropertyUnit model fields
                ],
                'maintainer' => [
                    // Maintainer model fields
                ],
            ]);

        // Test when maintenance request not found
        $response = $this->getJson('/api/auth/maintenance-requests/' . 9999, ['Authorization' => $this->token]);
        $response->assertStatus(404);
    }

    public function testUpdate()
    {
        $maintenanceRequest = MaintenanceRequest::factory()->create();

        $updateData = [
            'status' => 'COMPLETED',
        ];

        $response = $this->putJson('/api/auth/maintenance-requests/' . $maintenanceRequest->id, $updateData, ['Authorization' => $this->token]);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $updatedMaintenanceRequest = MaintenanceRequest::findOrFail($maintenanceRequest->id);
        $this->assertEquals($updateData['status'], $updatedMaintenanceRequest->status);
    }

    public function testDelete()
    {
        $maintenanceRequest = MaintenanceRequest::factory()->create();

        $response = $this->deleteJson('/api/auth/maintenance-requests/' . $maintenanceRequest->id, ['Authorization' => $this->token]);

        $response->assertStatus(200);
    }
}