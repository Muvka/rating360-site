<?php

namespace Database\Seeders\Company;

use App\Models\Company\Company;
use App\Models\Company\Direction;
use App\Models\Company\Division;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\Subdivision;
use App\Models\Shared\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/users.json'));
        $userData = json_decode($json);

        $admin = Employee::create([
            'first_name' => 'Admin',
            'email' => 'admin@localhost.ru',
            'password' => Hash::make('11111111'),
        ]);

        $admin->is_admin = true;
        $admin->save();

        foreach ($userData as $user) {
            if ( ! $user->email || ! $user->institution) {
                continue;
            }

            $company = null;
            $subdivision = null;

            $companySearch = str_replace(['"', "'", '«', '»'], '', $user->institution);
            $company = Company::whereRaw('REPLACE(REPLACE(REPLACE(REPLACE(LOWER(name), \'"\', \'\'), \'«\', \'\'), \'»\', \'\'), "\'", \'\') = ?',
                [strtolower($companySearch)])
                ->first();

            if ( ! $company) {
                $company = Company::create([
                    'name' => $user->institution,
                ]);
            }

            $subdivisionSearch = str_replace(['"', "'", '«', '»'], '', $user->department);
            $subdivision = Subdivision::whereRaw('REPLACE(REPLACE(REPLACE(REPLACE(LOWER(name), \'"\', \'\'), \'«\', \'\'), \'»\', \'\'), "\'", \'\') = ?',
                [strtolower($subdivisionSearch)])
                ->first();

            if (empty($subdivision)) {
                $subdivision = Subdivision::create([
                    'name' => $user->department,
                ]);
            }

            list($first_name, $last_name) = explode(' ', $user->name);

            $employeeData = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $user->email,
                'company_id' => $company->id,
                'company_subdivision_id' => $subdivision->id,
            ];

            if (App::Environment() !== 'production') {
                $employeeData['city_id'] = City::inRandomOrder()->first()->id;
                $employeeData['company_division_id'] = Division::inRandomOrder()->first()->id;
                $employeeData['company_subdivision_id'] = Subdivision::inRandomOrder()->first()->id;
                $employeeData['company_position_id'] = Position::inRandomOrder()->first()->id;
                $employeeData['company_level_id'] = 5;
            }

            $employee = Employee::create($employeeData);

            $employee->directions()
                ->attach(
                    Direction::inRandomOrder()
                        ->limit(random_int(1, 3))
                        ->get()
                );
        }
    }
}
