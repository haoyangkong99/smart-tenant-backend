<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(), // Create a user association
            'total_family_member' => $this->faker->numberBetween(1, 10),
        ];
    }
}