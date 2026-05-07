<?php

namespace Database\Seeders;

use App\Models\RemedyType;
use Illuminate\Database\Seeder;

class RemedyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['Crystal', 'Lal Kitab', 'Switch Word', 'Vedic Switch Word'];
        foreach ($types as $i => $name) {
            RemedyType::firstOrCreate(['name' => $name], ['sort_order' => $i, 'is_active' => true]);
        }
    }
}
