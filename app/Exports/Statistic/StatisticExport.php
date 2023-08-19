<?php

namespace App\Exports\Statistic;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StatisticExport implements FromView
{
    protected array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.statistic.statistic-table', [
            'data' => $this->data,
        ]);
    }
}
