<?php

namespace App\Imports\Rating;

use App\Models\Rating\Template;
use App\Models\Rating\Competence;
use App\Models\Rating\TemplateMarker;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class TemplateImport implements OnEachRow, WithHeadingRow, SkipsEmptyRows
{
    private Template $template;

    private Competence $competence;

    private int $markerSort = 1;

    public function __construct(string $name)
    {
        $this->template = Template::create([
            'name' => $name,
        ]);
    }

    public function onRow(Row $row): void
    {
        $row = $row->toArray();

        if (isset($row['kompetencii']) && trim($row['kompetencii'])) {
            $foundCompetence = Competence::whereRaw('LOWER(name) = ?', [Str::lower($row['kompetencii'])])
                ->first();

            $this->competence = $foundCompetence ?? Competence::create([
                'name' => trim($row['kompetencii']),
            ]);

            $this->template->competences()->attach($this->competence->id);

//            $this->markerSort = 1;
        }

        if ( ! isset($row['povedenceskie_markery']) || ! trim($row['povedenceskie_markery']) || ! $this->competence) {
            return;
        }

        $value = null;

        if (isset($row['nomer_cennosti']) && trim($row['nomer_cennosti']) && (int) $row['nomer_cennosti']) {
            switch ((int) $row['nomer_cennosti']) {
                case 1:
                {
                    $value = 'respect';
                    break;
                }
                case 2:
                {
                    $value = 'responsibility';
                    break;
                }
                case 3:
                {
                    $value = 'development';
                    break;
                }
                case 4:
                {
                    $value = 'team_leadership';
                    break;
                }
            }
        }

        TemplateMarker::create([
            'rating_template_id' => $this->template->id,
            'rating_competence_id' => $this->competence->id,
            'text' => trim($row['povedenceskie_markery']),
            'value' => $value,
            'answer_type' => isset($row['varianty_otvetov']) && trim($row['varianty_otvetov']) ? 'text' : 'default',
            'sort' => $this->markerSort++,
        ]);
    }
}
