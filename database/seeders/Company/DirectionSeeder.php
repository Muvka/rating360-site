<?php

namespace Database\Seeders\Company;

use App\Models\Company\Direction;
use Illuminate\Database\Seeder;

class DirectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(0, 39) as $index) {
            Direction::create([
                'name' => fake()->unique()->realText(30),
            ]);
        }
    }
}
