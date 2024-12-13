<?php

namespace Database\Factories;
use App\Models\PropertyUnit;
use App\Models\Property;
use App\Models\Maintainer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MaintenanceRequest>
 */
class MaintenanceRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'property_id' => Property::factory()->create()->id,
            'unit_id' => PropertyUnit::factory()->create()->id,
            'maintainer_id' => Maintainer::factory()->create()->id,
            'issue_type' => fake()->randomElement(['Plumbing', 'Electrical', 'Carpentry']),
            'status' => fake()->randomElement(['PENDING', 'COMPLETED', 'INPROGRESS', 'CANCELLED']),
            'issue_attachment' => null, // Or a valid file path
        ];
    }
}
