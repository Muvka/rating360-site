<?php

namespace App\Exports\Rating;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportCompetencesSheet implements FromView, WithTitle
{
    protected array $results;

    public function __construct(array $results = [])
    {
        $this->results = $results;
    }

    public function view(): View
    {
        return view('exports.rating.report-competences-sheet', [
            'results' => $this->results,
        ]);
    }

    public function title(): string
    {
        return 'По компетенциям';
    }
}
