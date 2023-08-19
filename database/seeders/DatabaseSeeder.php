<?php

namespace Database\Seeders;

use Database\Seeders\Company\DirectionSeeder;
use Database\Seeders\Company\DivisionSeeder;
use Database\Seeders\Company\EmployeeSeeder;
use Database\Seeders\Company\LevelSeeder;
use Database\Seeders\Company\PositionSeeder;
use Database\Seeders\Rating\ValueSeeder;
use Database\Seeders\Shared\CitySeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        if (App::Environment() === 'production') {
            $this->call([
                LevelSeeder::class,
                EmployeeSeeder::class,
                ValueSeeder::class,
            ]);
        } else {
            $this->call([
                CitySeeder::class,
                DivisionSeeder::class,
                PositionSeeder::class,
                LevelSeeder::class,
                DirectionSeeder::class,
                EmployeeSeeder::class,
                ValueSeeder::class,
            ]);
        }
    }
}
