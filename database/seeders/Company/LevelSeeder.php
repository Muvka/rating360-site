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
        $levels = [
            'L0',
            'L1',
            'L2',
            'L3',
            'Специалист',
        ];

        foreach ($levels as $level) {
            Level::create([
                'name' => $level,
            ]);
        }
    }
}
