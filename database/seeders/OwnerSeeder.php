<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Owner;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Owner::create([
            'full_name' => 'Owner User',
            'username' => 'owner',
            'email' => 'owner@gmail.com',
            'phone' => '1234567890',
            'password' => Hash::make('password100'),
            'status' => 'active',
            'email_verified_at' => now(),
            'language_preference' => 'en',
            'marital_status' => 'single',
            'two_factor_enabled' => false,
        ]);
    }
}
