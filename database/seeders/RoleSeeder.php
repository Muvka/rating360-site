<?php

namespace Database\Seeders;

use App\Models\User\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Администратор',
                'slug' => 'administrator',
            ],
            [
                'name' => 'Руководитель',
                'slug' => 'manager',
            ],
            [
                'name' => 'Сотрудник',
                'slug' => 'employee',
            ],
        ];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role['name'],
                'slug' => $role['slug'],
            ]);
        }
    }
}
