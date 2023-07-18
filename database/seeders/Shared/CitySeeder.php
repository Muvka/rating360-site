<?php

namespace Database\Seeders\Shared;

use App\Models\Shared\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(0, 9) as $index) {
            City::create([
                'name' => fake()->unique()->city(),
            ]);
        }
    }
}
