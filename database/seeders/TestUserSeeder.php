<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user
        User::create([
            'first_name' => 'Test',
            'middle_name' => 'User',
            'surname' => 'Account',
            'suffix' => null,
            'birth_date' => '1990-01-01',
            'gender' => 'male',
            'account_type' => 'individual',
            'contact_number' => '09123456789',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_verified' => true,
        ]);
    }
}
