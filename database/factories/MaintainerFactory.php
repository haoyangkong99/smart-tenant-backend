<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Property;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Maintainer>
 */
class MaintainerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'property_id' => Property::factory()->create()->id,
            'maintenance_type' => fake()->randomElement(['Plumbing', 'Electrical', 'Carpentry']),
            'description' => fake()->sentence(),
            'additional_info' => fake()->text(),
        ];
    }
}
