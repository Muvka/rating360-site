<?php

namespace Database\Seeders;

use Database\Seeders\Company\EmployeeLevelSeeder;
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
