<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\SubscriptionTransaction;
use App\Models\User;
use App\Models\SubscriptionPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class SubscriptionTransactionControllerTest extends TestCase
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
        // Create some sample SubscriptionTransactions
        SubscriptionTransaction::factory()->count(3)->create();

        $response = $this->getJson('/api/auth/subscription-transactions');

        $response->assertStatus(200);
    }

    public function testStore()
    {
        $user = User::factory()->create();
        $package = SubscriptionPackage::factory()->create();

        $data = [
            'user_id' => $user->id,
            'package_id' => $package->id,
            'amount' => 100.00,
            'payment_type' => 'Credit Card',
            'payment_status' => 'SUCCESS',
        ];

        $response = $this->postJson('/api/auth/subscription-transactions', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'transaction' => [
                    'id',
                    'user_id',
                    'package_id',
                    'amount',
                    'payment_type',
                    'payment_status',
                    'receipt',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }

    public function testShow()
    {
        $transaction = SubscriptionTransaction::factory()->create();

        $response = $this->getJson('/api/auth/subscription-transactions/' . $transaction->id);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $transaction->id,
                'user_id' => $transaction->user_id,
                'package_id' => $transaction->package_id,
                'amount' => $transaction->amount,
                'payment_type' => $transaction->payment_type,
                'payment_status' => $transaction->payment_status,
            ]);
    }

    public function testUpdate()
    {
        $transaction = SubscriptionTransaction::factory()->create();

        $updateData = [
            'payment_status' => 'COMPLETED',
        ];

        $response = $this->putJson('/api/subscription-transactions/' . $transaction->id, $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $updatedTransaction = SubscriptionTransaction::findOrFail($transaction->id);
        $this->assertEquals($updateData['payment_status'], $updatedTransaction->payment_status);
    }

    public function testDestroy()
    {
        $transaction = SubscriptionTransaction::factory()->create();

        $response = $this->deleteJson('/api/subscription-transactions/' . $transaction->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('subscription_transactions', ['id' => $transaction->id]);
    }

    public function testStoreWithInvalidData()
    {
        $data = [
            'user_id' => 1, // Assuming user with ID 1 doesn't exist
            'package_id' => 1, // Assuming package with ID 1 doesn't exist
            'amount' => -10, // Negative amount
            'payment_type' => '',
            'payment_status' => 'INVALID_STATUS',
        ];

        $response = $this->postJson('/api/subscription-transactions', $data);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'user_id',
                    'package_id',
                    'amount',
                    'payment_type',
                    'payment_status',
                ],
            ]);
    }

    public function testUpdateWithInvalidData()
    {
        $transaction = SubscriptionTransaction::factory()->create();

        $updateData = [
            'amount' => -5, // Negative amount
            'payment_status' => 'INVALID_STATUS',
        ];

        $response = $this->putJson('/api/subscription-transactions/' . $transaction->id, $updateData);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errors' => [
                    'amount',
                    'payment_status',
                ],
            ]);
    }

    public function testShowNotFound()
    {
        $response = $this->getJson('/api/subscription-transactions/9999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Transaction not found',
            ]);
    }

    public function testUpdateNotFound()
    {
        $response = $this->putJson('/api/subscription-transactions/9999', []);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Transaction not found',
            ]);
    }

    public function testDestroyNotFound()
    {
        $response = $this->deleteJson('/api/subscription-transactions/9999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Transaction not found',
            ]);
    }
}