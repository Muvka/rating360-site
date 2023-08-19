<?php

namespace Database\Seeders\Rating;

use App\Models\Rating\Value;
use Illuminate\Database\Seeder;

class ValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $values = [
            'Уважение и доверие',
            'Ответственность',
            'Развитие',
            'Командное лидерство',
        ];

        foreach ($values as $value) {
            Value::create(['name' => $value]);
        }
    }
}
