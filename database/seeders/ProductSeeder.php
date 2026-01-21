<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Define Product 1
        Product::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Premium Interior Pack',
                'price' => 12500.00,
                'description' => 'Complete interior architectural styling tailored to your specific project dossier.'
            ]
        );

        // Define Product 2
        Product::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Site Analysis Pro',
                'price' => 4200.00,
                'description' => 'Deep-dive topographical and environmental reporting for your registered site.'
            ]
        );
    }
}