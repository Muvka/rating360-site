<?php

namespace App\Imports\Rating;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Direction;
use App\Models\Company\Division;
use App\Models\Company\Level;
use App\Models\Company\Position;
use App\Models\Company\Subdivision;
use App\Models\Rating\Matrix;
use App\Models\Rating\MatrixTemplate;
use App\Models\Rating\MatrixTemplateClient;
use App\Models\Shared\City;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MatrixTemplateImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithChunkReading, WithBatchInserts
{
    private Matrix $matrix;

    public function __construct($martix)
    {
        $this->matrix = $martix;
    }

    public function model(array $row): void
    {
        $employee = $this->getEmployeeByFullName($row['sotrudnik']);

        if ( ! $employee) {
            Log::channel('excel_import')->info('Не удалось найти сотрудника "'.$row['sotrudnik'].'" при импорте в матрицу "'.$this->matrix->name.'"');
            return;
        }

        $foundTemplate = $this->matrix->templates->filter(function (MatrixTemplate $template) use ($employee) {
            return $template->company_employee_id === $employee->id;
        });

        if ($foundTemplate->isNotEmpty()) {
            return;
        }

        if ( ! $employee->city && isset($row['gorod']) && $row['gorod']) {
            $city = $this->getRecord(City::class, 'name', $row['gorod']);

            $employee->city_id = $city->id;
        }

        if ( ! $employee->company && isset($row['kompaniia']) && $row['kompaniia']) {
            $company = $this->getRecord(Company::class, 'name', $row['kompaniia']);

            $employee->company_id = $company->id;
        }

        if ( ! $employee->division && isset($row['otdel']) && $row['otdel']) {
            $division = $this->getRecord(Division::class, 'name', $row['otdel']);

            $employee->company_division_id = $division->id;
        }

        if ( ! $employee->subdivision && isset($row['podrazdelenie']) && $row['podrazdelenie']) {
            $subdivision = $this->getRecord(Subdivision::class, 'name', $row['podrazdelenie']);

            $employee->company_subdivision_id = $subdivision->id;
        }

        if ( ! $employee->position && isset($row['dolznost']) && $row['dolznost']) {
            $position = $this->getRecord(Position::class, 'name', $row['dolznost']);

            $employee->company_position_id = $position->id;
        }

        if ( ! $employee->level && isset($row['uroven_sotrudnika']) && $row['uroven_sotrudnika']) {
            $level = $this->getRecord(Level::class, 'name', $row['uroven_sotrudnika']);

            $employee->company_level_id = $level->id;
        }

        $directions = [];
        $clients = [];

        foreach ($row as $key => $value) {
            if (Str::startsWith($key, 'napravlenie') && trim($value)) {
                $directions[] = $value;
            }

            if ((Str::startsWith($key, 'vnutrennii_klient') || Str::startsWith($key, 'vnesnii_klient')) && trim($value)) {
                $client = $this->getEmployeeByFullName($value);

                if ( ! $client) {
                    continue;
                }

                $clients[] = new MatrixTemplateClient([
                    'company_employee_id' => $client->id,
                    'type' => Str::startsWith($key, 'vnutrennii_klient') ? 'inner' : 'outer',
                ]);
            }
        }

        if ( ! $employee->directions()->exists() && $directions) {
            foreach ($directions as $direction) {
                $directionRecord = $this->getRecord(Direction::class, 'name', $direction);

                $employee->directions()->attach($directionRecord);
            }
        }

        if ( ! $employee->directManager && isset($row['rukovoditel_1_neposredstvennyi']) && $row['rukovoditel_1_neposredstvennyi']) {
            $directManager = $this->getEmployeeByFullName($row['rukovoditel_1_neposredstvennyi']);

            if ($directManager) {
                $employee->direct_manager_id = $directManager->id;
            }
        }

        if ( ! $employee->directManager && isset($row['rukovoditel_2_funkcionalnyi']) && $row['rukovoditel_2_funkcionalnyi']) {
            $functionalManager = $this->getEmployeeByFullName($row['rukovoditel_2_funkcionalnyi']);

            if ($functionalManager) {
                $employee->functional_manager_id = $functionalManager->id;
            }
        }

        $matrixTemplate = $this->matrix->templates()->create(['company_employee_id' => $employee->id]);

        $matrixTemplate->clients()->saveMany($clients);
        $employee->save();
    }

    private function getEmployeeByFullName(string $fullName): Employee|null
    {
        $nameParts = explode(' ', $fullName);
        $lastName = $nameParts[0];
        $firstName = $nameParts[1] ?? '';
        $middleName = $nameParts[2] ?? '';

        $foundEmployees = Employee::when($firstName, function (Builder $query, string $firstName) {
            $query->whereRaw('LOWER(first_name) = ?', [Str::lower($firstName)]);
        })
            ->whereRaw('LOWER(last_name) = ?', [Str::lower($lastName)])
            ->get();

        if ($foundEmployees->count() > 1 && $middleName) {
            $filteredEmployees = $foundEmployees->filter(function (Employee $employee) use ($middleName) {
                return $employee->middle_name && (Str::lower($employee->middle_name) === Str::lower($middleName));
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

        $record = $model::whereRaw('REPLACE(REPLACE(REPLACE(REPLACE(LOWER('.$attribute.'), \'"\', \'\'), \'«\', \'\'), \'»\', \'\'), "\'", \'\') = ?',
            [Str::lower(trim($valueSearch))])
            ->first();

        if ( ! $record) {
            $record = $model::create([$attribute => $value]);
        }

        return $record;
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function batchSize(): int
    {
        return 50;
    }
}
