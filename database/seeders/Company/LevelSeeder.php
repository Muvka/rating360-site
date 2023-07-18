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
            'Руководитель (ген.дир.)',
            'Руководитель 1 уровня',
            'Руководитель 2 уровня',
            'Руководитель 3 уровня',
            'Специалист',
        ];

        foreach ($levels as $level) {
            Level::create([
                'name' => $level,
            ]);
        }
    }
}
