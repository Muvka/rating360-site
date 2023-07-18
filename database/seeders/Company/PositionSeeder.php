<?php

namespace Database\Seeders\Company;

use App\Models\Company\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(0, 29) as $index) {
            Position::create([
                'name' => fake()->unique()->jobTitle(),
            ]);
        }
    }
}
