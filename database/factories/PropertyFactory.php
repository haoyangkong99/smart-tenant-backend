<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
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
            'type' => 'Service Apartment',
            'name' => 'M Vertica',
            'description' => 'test description',
            'address' => 'M Vertica, Jalan Cheras',
            'country' => 'Malaysia',
            'state' => 'W.P Kuala Lumpur',
            'city' => 'Kuala Lumpur',
            'post_code' => '56000',
        ];
    }
}
