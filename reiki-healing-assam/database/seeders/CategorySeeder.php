<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Health',       'description' => 'Physical and mental wellness insights.'],
            ['name' => 'Relationship', 'description' => 'Interpersonal and social harmony.'],
            ['name' => 'Career',       'description' => 'Professional growth and direction.'],
            ['name' => 'Money',        'description' => 'Financial wellness and abundance.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description'], 'is_active' => true]
            );
        }
    }
}
