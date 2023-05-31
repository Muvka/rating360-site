<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/users.json'));
        $userData = json_decode($json);
        $userPasswords = [];

        foreach ($userData as $user) {
            if (!$user->email) {
                continue;
            }

            $companyId = null;
            $subdivisionId = null;
            $password = Str::password(10);
            $userPasswords[] = $user->name . ' (' . $user->email . '): ' . $password;

            if ($user->institution) {
                $companySearch = str_replace(['"', "'", '«', '»'], '', $user->institution);
                $company = DB::table('companies')
                    ->whereRaw('REPLACE(REPLACE(REPLACE(REPLACE(LOWER(name), \'"\', \'\'), \'«\', \'\'), \'»\', \'\'), "\'", \'\') = ?', [strtolower($companySearch)])
                    ->first();

                if (empty($company)) {
                    $companyId = DB::table('companies')->insertGetId([
                        'name' => $user->institution,
                    ]);
                } else {
                    $companyId = $company->id;
                }
            }

            if ($user->department) {
                $subdivisionSearch = str_replace(['"', "'", '«', '»'], '', $user->department);
                $subdivision = DB::table('subdivisions')
                    ->whereRaw('REPLACE(REPLACE(REPLACE(REPLACE(LOWER(name), \'"\', \'\'), \'«\', \'\'), \'»\', \'\'), "\'", \'\') = ?', [strtolower($subdivisionSearch)])
                    ->first();

                if (empty($subdivision)) {
                    $subdivisionId = DB::table('subdivisions')->insertGetId([
                        'name' => $user->department,
                    ]);
                } else {
                    $subdivisionId = $subdivision->id;
                }
            }

            DB::table('users')->insert([
                'name' => $user->name,
                'email' => $user->email,
                'company_id' => $companyId,
                'subdivision_id' => $subdivisionId,
                'password' => Hash::make($password),
            ]);
        }

        file_put_contents(database_path('seeders/data/user_passwords.txt'), implode(PHP_EOL, $userPasswords));
    }
}
