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
        }

        if ( ! isset($row['povedenceskie_markery']) || ! trim($row['povedenceskie_markery']) || ! $this->competence) {
            return;
        }

        TemplateMarker::create([
            'rating_template_id' => $this->template->id,
            'rating_competence_id' => $this->competence->id,
            'text' => Str::ucfirst(trim($row['povedenceskie_markery'])),
            'rating_value_id' => $row['nomer_cennosti'] ?? null,
            'answer_type' => isset($row['varianty_otvetov']) && trim($row['varianty_otvetov']) ? 'text' : 'default',
            'sort' => $this->markerSort++,
        ]);
    }
}
