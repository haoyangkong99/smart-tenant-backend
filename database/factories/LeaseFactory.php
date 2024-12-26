<?php

namespace Database\Factories;

use App\Models\Lease;
use App\Models\PropertyUnit;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tenant;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lease>
 */
class LeaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $PropertyUnit=PropertyUnit::factory()->create();
        $Tenant=Tenant::factory()->create();
        return [
            'property_id' => $PropertyUnit->property_id, // Adjust range as needed
            'unit_id' =>  $PropertyUnit->id, // Adjust range as needed
            'tenant_id' => $Tenant->id, // Adjust range as needed
            'lease_number' => 'Lease-' . fake()->unique()->randomNumber(5),
            'rent_start_date' => '2024-01-01',
            'rent_end_date' => '2025-01-01',
            'rent_amount' => fake()->randomFloat(2, 1000, 5000),
            'rent_type' => fake()->randomElement(['Daily', 'Weekly', 'Monthly', 'Yearly']),
            'terms' => fake()->numberBetween(6, 24),
            'deposit_amount' => fake()->randomFloat(2, 500, 2000),
            'deposit_description' => fake()->sentence(),
            'status' => fake()->randomElement(['DRAFT','ACTIVE','PENDING','RENEWAL_PENDING','TERMINATED','COMPLETED','CANCELLED','OVERDUE','ONHOLD']),
        ];
    }
}