<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\SubscriptionPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Factories\SubscriptionPackageFactory;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SubscriptionControllerTest extends TestCase
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
        // Create sample subscriptions using factory
        SubscriptionPackage::factory()->count(3)->create();

        $response = $this->getJson('/api/auth/subscriptions');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    '*' => [
                        'id',
                        'title',
                        'amount',
                        'interval',
                        'staff_limit',
                        'property_limit',
                        'tenant_limit',
                        'created_at',
                        'updated_at',
                    ],
                ]);

        // Test when no subscriptions exist
        SubscriptionPackage::truncate();
        $response = $this->getJson('/api/auth/subscriptions');
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $data = [
            'title' => 'Premium Plan',
            'amount' => 99.99,
            'interval' => 12,
            'staff_limit' => 10,
            'property_limit' => 5,
            'tenant_limit' => 100,
        ];

        $response = $this->postJson('/api/auth/subscriptions', $data, ['Authorization' => $this->token]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'subscription' => [
                        'id',
                        'title',
                        'amount',
                        'interval',
                        'staff_limit',
                        'property_limit',
                        'tenant_limit',
                        'created_at',
                        'updated_at',
                    ],
                ]);
    }

    public function testShow()
    {
        // Create a subscription using factory
        $subscription = SubscriptionPackage::factory()->create();

        $response = $this->getJson('/api/auth/subscriptions/' . $subscription->id);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'id',
                    'title',
                    'amount',
                    'interval',
                    'staff_limit',
                    'property_limit',
                    'tenant_limit',
                    'created_at',
                    'updated_at',
                ]);

        // Test when subscription not found
        $response = $this->getJson('/api/auth/subscriptions/' . 9999); // Non-existent ID
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $subscription = SubscriptionPackage::factory()->create();

        $data = [
            'title' => 'Updated Plan',
            'amount' => 199.99,
            'interval'=>365,
            'staff_limit'=>35,
            'property_limit'=>30,
            'tenant_limit'=>70
        ];

        $response = $this->putJson('/api/auth/subscriptions/' . $subscription->id, $data, ['Authorization' => $this->token]);

        $response->assertStatus(200)
                ->assertJsonFragment($data);
    }

    public function testDelete()
    {
        $subscription = SubscriptionPackage::factory()->create();

        $response = $this->deleteJson('/api/auth/subscriptions/' . $subscription->id, ['Authorization' => $this->token]);

        $response->assertStatus(200);
    }
}