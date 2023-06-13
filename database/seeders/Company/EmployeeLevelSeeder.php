<?php

namespace Database\Seeders\Company;

use App\Models\Company\EmployeeLevel;
use Illuminate\Database\Seeder;

class EmployeeLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            'Cпециалист',
            'Руководитель (ген.дир.)',
            'Руководитель 1 уровня',
            'Руководитель 2 уровня',
            'Руководитель 3 уровня',
        ];

        foreach ($levels as $level) {
            EmployeeLevel::create([
                'name' => $level,
            ]);
        }
    }
}
