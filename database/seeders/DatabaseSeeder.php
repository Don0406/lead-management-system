<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lead;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@leadmanager.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create Sales Manager
        User::create([
            'name' => 'Sales Manager',
            'email' => 'manager@leadmanager.com',
            'password' => bcrypt('password'),
            'role' => 'sales_manager'
        ]);

        // Create Sales Reps
        $salesReps = [
            ['name' => 'John Doe', 'email' => 'john@leadmanager.com'],
            ['name' => 'Jane Smith', 'email' => 'jane@leadmanager.com'],
            ['name' => 'Bob Wilson', 'email' => 'bob@leadmanager.com'],
        ];

        foreach ($salesReps as $rep) {
            User::create([
                'name' => $rep['name'],
                'email' => $rep['email'],
                'password' => bcrypt('password'),
                'role' => 'sales_rep'
            ]);
        }

        // Create Sample Leads
        $sources = ['Website', 'Referral', 'Social Media', 'Email Campaign', 'Event'];
        $statuses = ['new', 'contacted', 'qualified', 'proposal', 'negotiation'];
        
        for ($i = 1; $i <= 20; $i++) {
            Lead::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'company' => fake()->company(),
                'title' => fake()->jobTitle(),
                'source' => $sources[array_rand($sources)],
                'status' => $statuses[array_rand($statuses)],
                'estimated_value' => rand(1000, 50000),
                'notes' => fake()->paragraph(),
                'assigned_to' => rand(2, 5), // Assign to existing users
                'created_by' => rand(1, 5),
                'contacted_at' => rand(0, 1) ? now()->subDays(rand(1, 30)) : null,
            ]);
        }
    }
}