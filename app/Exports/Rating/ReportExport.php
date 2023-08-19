<?php

namespace App\Exports\Rating;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets
{
    protected array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new ReportCompetencesSheet($this->data['competences']),
            new ReportMarkersSheet($this->data['markers']),
            new ReportFeedbackSheet($this->data['feedback']),
        ];
    }
}
