<?php

namespace Database\Seeders\Company;

use App\Models\Company\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $levels = [
            [
                'name' => 'L0',
                'is_manager' => true,
                'requires_manager' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'L1',
                'is_manager' => true,
                'requires_manager' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'L2',
                'is_manager' => true,
                'requires_manager' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'L3',
                'is_manager' => true,
                'requires_manager' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Специалист',
                'is_manager' => false,
                'requires_manager' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        Level::insert($levels);
    }
}
