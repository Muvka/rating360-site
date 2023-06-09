<?php

namespace App\Imports\Rating;

use App\Models\Rating\Employee;
use App\Models\Rating\EmployeeDirection;
use App\Models\Rating\EmployeeDivision;
use App\Models\Rating\EmployeeLevel;
use App\Models\Rating\EmployeePosition;
use App\Models\Rating\EmployeeSubdivision;
use App\Models\Rating\Matrix;
use App\Models\Rating\MatrixTemplateClient;
use App\Models\Shared\City;
use App\Models\Shared\Company;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MatrixTemplateImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    private Matrix $martix;

    public function __construct($martix)
    {
        $this->martix = $martix;
    }

    public function model(array $row): void
    {
//        dd($row);

        $employee = $this->getEmployeeByFullName($row['sotrudnik']);

        if ( ! $employee) {
            return;
        }

        $matrixTemplate = $this->martix->templates()->create(['rating_employee_id' => $employee->id]);

        if ( ! $employee->city && $row['gorod']) {
            $city = $this->getRecord(City::class, 'name', $row['gorod']);

            $employee->city_id = $city->id;
        }

        if ( ! $employee->company && $row['kompaniia']) {
            $company = $this->getRecord(Company::class, 'name', $row['kompaniia']);

            $employee->company_id = $company->id;
        }

        if ( ! $employee->division && $row['otdel']) {
            $division = $this->getRecord(EmployeeDivision::class, 'name', $row['otdel']);

            $employee->rating_employee_division_id = $division->id;
        }

        if ( ! $employee->subdivision && $row['podrazdelenie']) {
            $subdivision = $this->getRecord(EmployeeSubdivision::class, 'name', $row['podrazdelenie']);

            $employee->rating_employee_subdivision_id = $subdivision->id;
        }

        if ( ! $employee->position && $row['dolznost']) {
            $position = $this->getRecord(EmployeePosition::class, 'name', $row['dolznost']);

            $employee->rating_employee_position_id = $position->id;
        }

        if ( ! $employee->level && $row['uroven_sotrudnika']) {
            $level = $this->getRecord(EmployeeLevel::class, 'name', $row['uroven_sotrudnika']);

            $employee->rating_employee_level_id = $level->id;
        }

        if ( ! $employee->directManager && $row['rukovoditel_1_neposredstvennyi']) {
            $directManager = $this->getEmployeeByFullName($row['rukovoditel_1_neposredstvennyi']);

            if ($directManager) {
                $directManager->is_manager = true;
                $directManager->save();

                $employee->direct_manager_id = $directManager->id;
            }
        }

        if ( ! $employee->directManager && $row['rukovoditel_2_funkcionalnyi']) {
            $functionalManager = $this->getEmployeeByFullName($row['rukovoditel_2_funkcionalnyi']);

            if ($functionalManager) {
                $functionalManager->is_manager = true;
                $functionalManager->save();

                $employee->functional_manager_id = $functionalManager->id;
            }
        }

        $directions = [];
        $innerClients = [];
        $outerClients = [];

        foreach ($row as $key => $value) {
            if (Str::startsWith($key, 'napravlenie') && trim($value)) {
                $directions[] = $value;
            }

            if (Str::startsWith($key, 'vnutrennii_klient') && trim($value)) {
                $innerClients[] = $value;
            }

            if (Str::startsWith($key, 'vnesnii_klient') && trim($value)) {
                $outerClients[] = $value;
            }
        }

        if ( ! $employee->directions()->exists() && $directions) {
            foreach ($directions as $direction) {
                $directionRecord = $this->getRecord(EmployeeDirection::class, 'name', $direction);

                $employee->directions()->attach($directionRecord);
            }
        }

        if (!empty($innerClients)) {
            foreach ($innerClients as $innerClient) {
                $client = $this->getEmployeeByFullName($innerClient);

                if (!$client) {
                    continue;
                }

                MatrixTemplateClient::create([
                    'rating_employee_id' => $client->id,
                    'rating_matrix_template_id' => $matrixTemplate->id,
                ]);
            }
        }

        if (!empty($outerClients)) {
            foreach ($outerClients as $outerClient) {
                $client = $this->getEmployeeByFullName($outerClient);

                if (!$client) {
                    continue;
                }

                MatrixTemplateClient::create([
                    'rating_employee_id' => $client->id,
                    'rating_matrix_template_id' => $matrixTemplate->id,
                    'outer' => true,
                ]);
            }
        }

        $employee->save();
    }

    private function getEmployeeByFullName($fullName): Employee|null
    {
        $nameParts = explode(' ', $fullName);

        $foundEmployees = Employee::with('user')
            ->whereHas('user', function ($query) use ($nameParts) {
                $query->whereRaw('LOWER(first_name) = ?', [Str::lower($nameParts[1])])
                    ->whereRaw('LOWER(last_name) = ?', [Str::lower($nameParts[0])]);
            })->get();

        if ($foundEmployees->count() > 1 && count($nameParts) === 3) {
            $filteredEmployees = $foundEmployees->filter(function ($employee) use ($nameParts) {
                return $employee->user->middle_name && (Str::lower($employee->user->middle_name) === Str::lower($nameParts[2]));
            });

            if ($filteredEmployees->isNotEmpty()) {
                return $filteredEmployees->first();
            }
        }

        return $foundEmployees->first();
    }

    private function getRecord($model, string $attribute, string $value)
    {
        $record = $model::whereRaw('REPLACE(REPLACE(REPLACE(REPLACE(LOWER(' . $attribute . '), \'"\', \'\'), \'«\', \'\'), \'»\', \'\'), "\'", \'\') = ?',
            [strtolower($value)])
            ->first();

        if ( ! $record) {
            $record = $model::create([$attribute => $value]);
        }

        return $record;
    }
}
