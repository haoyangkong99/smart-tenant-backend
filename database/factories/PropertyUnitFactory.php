<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Property;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyUnit>
 */
class PropertyUnitFactory extends Factory
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
            'name'=> 'Unit 1',
            'room_num'=>4,
            'property_id'=>Property::factory()->create()->id
        ];
    }
}
