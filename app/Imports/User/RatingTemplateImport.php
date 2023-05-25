<?php

namespace App\Imports\User;

use App\Models\UserRatingTemplate;
use App\Models\UserRatingTemplateCompetence;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class RatingTemplateImport implements OnEachRow, WithHeadingRow
{
    private UserRatingTemplate $userRatingTemplate;

    private UserRatingTemplateCompetence $lastCompetence;

    private int $competenceSort = 1;

    private int $markerSort = 1;

    public function __construct(string $name)
    {
        $this->userRatingTemplate = UserRatingTemplate::create([
            'name' => $name,
        ]);
    }

    public function onRow(Row $row): void
    {
        $row = Arr::flatten($row->toArray());

        if (isset($row[0]) && trim($row[0])) {
            $this->lastCompetence = $this->userRatingTemplate->competences()->create([
                'name' => trim($row[0]),
                'sort' => $this->competenceSort++,
            ]);

            $this->markerSort = 1;
        }

        if (!isset($row[1]) || !trim($row[1]) || !$this->lastCompetence) {
            return;
        }

        $value = null;

        if (isset($row[2]) && trim($row[2]) && (int)$row[2]) {
            switch ((int)$row[2]) {
                case 1: {
                    $value = 'respect';
                    break;
                }
                case 2: {
                    $value = 'responsibility';
                    break;
                }
                case 3: {
                    $value = 'development';
                    break;
                }
                case 4: {
                    $value = 'team_leadership';
                    break;
                }
            }
        }

        $this->lastCompetence->markers()->create([
            'text' => trim($row[1]),
            'value' => $value,
            'answer_type' => isset($row[3]) && trim($row[3]) ? 'text' : 'default',
            'sort' => $this->markerSort++,
        ]);
    }
}
