<?php

namespace Database\Seeders;

use Database\Seeders\Rating\EmployeeDivisionSeeder;
use Database\Seeders\Rating\EmployeeLevelSeeder;
use Database\Seeders\Rating\EmployeePositionSeeder;
use Database\Seeders\Shared\CitySeeder;
use Database\Seeders\Shared\UserSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            EmployeeLevelSeeder::class,
            UserSeeder::class,
        ]);
    }
}
