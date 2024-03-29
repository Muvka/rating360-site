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
        $json = file_get_contents(database_path('seeders/Data/users.json'));
        $userData = json_decode($json);

        $admin = Employee::firstOrCreate(['email' => 'admin@localhost.ru'], [
            'first_name' => 'Admin',
            'password' => Hash::make('11111111'),
        ]);

        $admin->is_admin = true;
        $admin->save();

        foreach ($userData as $user) {
            if (! $user->email || ! $user->institution) {
                continue;
            }

            $city = null;
            $company = null;
            $subdivision = null;

            if (isset($user->city)) {
                $city = City::firstOrCreate(['name' => $user->city]);
            }

            $companySearch = str_replace(['"', "'", '«', '»'], '', $user->institution);
            $company = Company::whereRaw('REPLACE(REPLACE(REPLACE(REPLACE(LOWER(name), \'"\', \'\'), \'«\', \'\'), \'»\', \'\'), "\'", \'\') = ?',
                [strtolower($companySearch)])
                ->first();

            if (! $company) {
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

            [$first_name, $last_name] = explode(' ', $user->name);

            $employeeData = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'city_id' => $city?->id,
                'company_id' => $company->id,
                'company_subdivision_id' => $subdivision->id,
            ];

            if (App::Environment() !== 'production') {
                $employeeData['city_id'] = $employeeData['city_id'] ?? City::inRandomOrder()->first()->id;
                $employeeData['company_division_id'] = Division::inRandomOrder()->first()->id;
                $employeeData['company_position_id'] = Position::inRandomOrder()->first()->id;
                $employeeData['company_level_id'] = 5;
            }

            $employee = Employee::updateOrCreate(['email' => $user->email], $employeeData);

            if (App::Environment() !== 'production') {
                $employee->directions()
                    ->sync(
                        Direction::inRandomOrder()
                            ->limit(random_int(1, 3))
                            ->get()
                    );
            }
        }
    }
}
