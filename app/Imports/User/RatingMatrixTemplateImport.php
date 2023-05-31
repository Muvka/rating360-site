<?php

namespace App\Imports\User;

use App\Models\UserRatingMatrix;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RatingMatrixTemplateImport implements ToModel, WithHeadingRow
{
    private UserRatingMatrix $martix;

    public function __construct($martix)
    {
        $this->martix = $martix;
    }

    public function model(array $row): void
    {
        $matrixTemplate = $this->martix->templates()->create(
            [
                'user_id' => 1,
                'division' => $row['otdel'],
                'subdivision' => $row['podrazdelenie'],
                'position' => $row['dolznost'],
                'level' => $row['uroven_sotrudnika'],
                'company' => $row['kompaniia'],
                'city' => $row['gorod'],
            ]
        );

        $directions = [];
        $inner_clients = [];
        $outer_clients = [];

        foreach ($row as $key => $value) {
            if (Str::startsWith($key, 'napravlenie')) {
                $directions[] = $value;
            }

            if (Str::startsWith($key, 'vnutrennii_klient')) {
                $inner_clients[] = $value;
            }

            if (Str::startsWith($key, 'vnesnii_klient')) {
                $outer_clients[] = $value;
            }
        }

        if ( ! empty($directions)) {
            foreach ($directions as $direction) {
                $matrixTemplate->directions()->create([
                    'name' => $direction,
                ]);
            }
        }
    }
}
