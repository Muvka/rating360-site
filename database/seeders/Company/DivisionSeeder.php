<?php

namespace Database\Seeders\Company;

use App\Models\Company\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(0, 19) as $index) {
            Division::create([
                'name' => fake()->unique()->realText(30),
            ]);
        }
    }
}
