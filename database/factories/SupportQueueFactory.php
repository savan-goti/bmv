<?php

namespace Database\Factories;

use App\Models\SupportQueue;
use App\Models\SupportDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupportQueueFactory extends Factory
{
    protected $model = SupportQueue::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true) . ' Queue',
            'description' => fake()->sentence(),
            'department_id' => null,
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

    public function forDepartment(SupportDepartment $department): static
    {
        return $this->state(fn (array $attributes) => [
            'department_id' => $department->id,
        ]);
    }
}
