<?php

namespace App\Imports\Rating;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Direction;
use App\Models\Company\Division;
use App\Models\Company\EmployeeLevel;
use App\Models\Company\EmployeePosition;
use App\Models\Company\Subdivision;
use App\Models\Rating\Matrix;
use App\Models\Rating\MatrixTemplate;
use App\Models\Shared\City;
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
        $employee = $this->getEmployeeByFullName($row['sotrudnik']);

        if ( ! $employee) {
            return;
        }

        $foundTemplate = $this->martix->templates->filter(function (MatrixTemplate $template) use ($employee) {
            return $template->company_employee_id === $employee->id;
        });

        if ($foundTemplate->isNotEmpty()) {
            return;
        }

        if ( ! $employee->city && $row['gorod']) {
            $city = $this->getRecord(City::class, 'name', $row['gorod']);

            $employee->city_id = $city->id;
        }

        if ( ! $employee->company && $row['kompaniia']) {
            $company = $this->getRecord(Company::class, 'name', $row['kompaniia']);

            $employee->company_id = $company->id;
        }

        if ( ! $employee->division && $row['otdel']) {
            $division = $this->getRecord(Division::class, 'name', $row['otdel']);

            $employee->company_division_id = $division->id;
        }

        if ( ! $employee->subdivision && $row['podrazdelenie']) {
            $subdivision = $this->getRecord(Subdivision::class, 'name', $row['podrazdelenie']);

            $employee->company_subdivision_id = $subdivision->id;
        }

        if ( ! $employee->position && $row['dolznost']) {
            $position = $this->getRecord(EmployeePosition::class, 'name', $row['dolznost']);

            $employee->company_employee_position_id = $position->id;
        }

        if ( ! $employee->level && $row['uroven_sotrudnika']) {
            $level = $this->getRecord(EmployeeLevel::class, 'name', $row['uroven_sotrudnika']);

            $employee->company_employee_level_id = $level->id;
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
                $directionRecord = $this->getRecord(Direction::class, 'name', $direction);

                $employee->directions()->attach($directionRecord);
            }
        }

        $matrixTemplate = $this->martix->templates()->create(['company_employee_id' => $employee->id]);

        if ( ! $employee->directManager && $row['rukovoditel_1_neposredstvennyi']) {
            $directManager = $this->getEmployeeByFullName($row['rukovoditel_1_neposredstvennyi']);

            if ($directManager) {
                $employee->direct_manager_id = $directManager->id;
                $matrixTemplate->clients()->create([
                    'company_employee_id' => $directManager->id,
                    'type' => 'manager',
                ]);
            }
        }

        if ( ! $employee->directManager && $row['rukovoditel_2_funkcionalnyi']) {
            $functionalManager = $this->getEmployeeByFullName($row['rukovoditel_2_funkcionalnyi']);

            if ($functionalManager) {
                $employee->functional_manager_id = $functionalManager->id;
                $matrixTemplate->clients()->create([
                    'company_employee_id' => $functionalManager->id,
                    'type' => 'manager',
                ]);
            }
        }

        if (!empty($innerClients)) {
            foreach ($innerClients as $innerClient) {
                $client = $this->getEmployeeByFullName($innerClient);

                if (!$client) {
                    continue;
                }

                $matrixTemplate->clients()->create([
                    'company_employee_id' => $client->id,
                    'type' => 'inner',
                ]);
            }
        }

        if (!empty($outerClients)) {
            foreach ($outerClients as $outerClient) {
                $client = $this->getEmployeeByFullName($outerClient);

                if (!$client) {
                    continue;
                }

                $matrixTemplate->clients()->create([
                    'company_employee_id' => $client->id,
                    'type' => 'outer',
                ]);
            }
        }

        $employee->save();
    }

    private function getEmployeeByFullName(string $fullName): Employee|null
    {
        $nameParts = explode(' ', $fullName);

        $foundEmployees = Employee::whereRaw('LOWER(first_name) = ?', [Str::lower($nameParts[1])])
            ->whereRaw('LOWER(last_name) = ?', [Str::lower($nameParts[0])])
            ->get();

        if ($foundEmployees->count() > 1 && count($nameParts) === 3) {
            $filteredEmployees = $foundEmployees->filter(function (Employee $employee) use ($nameParts) {
                return $employee->middle_name && (Str::lower($employee->middle_name) === Str::lower($nameParts[2]));
            });

            if ($filteredEmployees->isNotEmpty()) {
                return $filteredEmployees->first();
            }
        }

        return $foundEmployees->first();
    }

    private function getRecord($model, string $attribute, string $value)
    {
        $valueSearch = str_replace(['"', "'", '«', '»'], '', $value);

        $record = $model::whereRaw('REPLACE(REPLACE(REPLACE(REPLACE(LOWER(' . $attribute . '), \'"\', \'\'), \'«\', \'\'), \'»\', \'\'), "\'", \'\') = ?',
            [Str::lower(trim($valueSearch))])
            ->first();

        if ( ! $record) {
            $record = $model::create([$attribute => $value]);
        }

        return $record;
    }
}
