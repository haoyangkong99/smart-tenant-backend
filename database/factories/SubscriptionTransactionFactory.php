<?php

namespace Database\Factories;

use App\Models\SubscriptionPackage;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubscriptionTransaction>
 */
class SubscriptionTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'user_id' => User::factory()->create()->id,
            'package_id' => SubscriptionPackage::factory()->create(),
            'amount' => 100.00,
            'payment_type' => 'Credit Card',
            'payment_status' => 'SUCCESS',
        ];
    }
}
