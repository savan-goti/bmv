<?php

namespace Database\Factories;

use App\Models\SupportTeamMember;
use App\Enums\SupportRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class SupportTeamMemberFactory extends Factory
{
    protected $model = SupportTeamMember::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'password' => Hash::make('password'),
            'role' => fake()->randomElement(SupportRole::values()),
            'departments' => null,
            'default_queues' => null,
            'status' => fake()->randomElement(['active', 'disabled']),
            'notification_method' => fake()->randomElement(['email', 'in_app', 'both']),
            'tickets_assigned' => fake()->numberBetween(0, 100),
            'open_tickets' => fake()->numberBetween(0, 50),
            'avg_response_time' => fake()->randomFloat(2, 1, 60),
            'email_verified_at' => now(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function disabled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'disabled',
        ]);
    }

    public function withRole(string $role): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => $role,
        ]);
    }
}
