<?php

namespace Database\Factories;

use App\Models\SupportDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupportDepartmentFactory extends Factory
{
    protected $model = SupportDepartment::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true) . ' Department',
            'description' => fake()->sentence(),
            'status' => 'active',
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
