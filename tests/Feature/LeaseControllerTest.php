<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Lease;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class LeaseControllerTest extends TestCase
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
        $lease = Lease::factory()->create();

        $response = $this->getJson('/api/auth/leases', ['Authorization' => $this->token]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [

                ],
            ]);
    }

    public function testStore()
    {
        $property = Property::factory()->create();
        $unit = PropertyUnit::factory()->create(['property_id' => $property->id]);
        $tenant = Tenant::factory()->create();

        $data = [
            'property_id' => $property->id,
            'unit_id' => $unit->id,
            'tenant_id' => $tenant->id,
            'lease_number' => 'Lease-123',
            'rent_start_date' => now()->format('Y-m-d'),
            'rent_end_date' => now()->addYears(1)->format('Y-m-d'),
            'rent_amount' => 1000.00,
            'rent_type' => 'Monthly',
            'terms' => 12,
            'deposit_amount' => 500.00,
            'deposit_description' => 'Security Deposit',
            'status' => 'ACTIVE',
            'contract'=>''
        ];

        $response = $this->postJson('/api/auth/leases', $data, ['Authorization' => $this->token]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => []
            ]);
    }

    public function testShow()
    {
        $lease = Lease::factory()->create();

        $response = $this->getJson('/api/auth/leases/' . $lease->id, ['Authorization' => $this->token]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ]);
    }

    public function testUpdate()
    {
        $lease = Lease::factory()->create();


        $lease->rent_amount=1200;

        $response = $this->putJson('/api/auth/leases/' . $lease->id, $lease->toArray(), ['Authorization' => $this->token]);
        $response->assertStatus(200);

        $updatedLease = Lease::findOrFail($lease->id);
        $this->assertEquals($lease['rent_amount'], $updatedLease->rent_amount);
    }

    public function testDestroy()
    {
        $lease = Lease::factory()->create();

        $response = $this->deleteJson('/api/auth/leases/' . $lease->id, ['Authorization' => $this->token]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('leases', ['id' => $lease->id]);
    }

    // Add more test cases for specific scenarios, e.g.,
    // - Invalid input (e.g., missing required fields, invalid data types)
    // - File uploads
    // - Error handling (e.g., not found, validation errors)
}