<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubscriptionPackage>
 */
class SubscriptionPackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'Test Subscription Package',
            'amount' => 120,
            'interval' => 365,
            'staff_limit' => 10,
            'property_limit' => 20,
            'tenant_limit' => 50,
            //
        ];
    }
}
