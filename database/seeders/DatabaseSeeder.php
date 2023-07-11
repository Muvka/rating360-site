<?php

namespace Database\Seeders;

use Database\Seeders\Company\EmployeeLevelSeeder;
use Database\Seeders\Company\EmployeeSeeder;
use Database\Seeders\Rating\ValueSeeder;
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
            EmployeeSeeder::class,
            ValueSeeder::class,
        ]);
    }
}
