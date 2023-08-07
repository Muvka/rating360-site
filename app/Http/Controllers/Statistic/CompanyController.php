<?php

namespace App\Http\Controllers\Statistic;

use App\Exports\Statistic\StatisticExport;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Statistic\ClientCompetence;
use App\Models\Statistic\Result;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CompanyController extends Controller
{
    public function index(): Response
    {
        $filters = Request::only(['year', 'companies']);

        return Inertia::render('Statistic/StatisticPage', [
            'title' => 'Статистика по компании',
            'fields' => $this->getFormFields(),
            'filters' => $filters,
            'statistic' => $filters ? $this->getStatistic() : [],
            'exportUrl' => route('client.statistic.company.export', $filters)
        ]);
    }

    public function export(): BinaryFileResponse
    {
        $fileName = 'Статистика-по-компании-'.Carbon::now()->format('Y-m-d').'.xlsx';

        return Excel::download(new StatisticExport($this->getStatistic()), $fileName);
    }

    private function getStatistic(): array
    {
        $columns = [
            [
                'key' => 'competence',
                'label' => 'Компетенция'
            ], [
                'key' => 'averageRating',
                'label' => 'Средняя оценка'
            ], [
                'key' => 'averageRatingWithoutSelf',
                'label' => 'Средняя оценка (без самооценки)'
            ]
        ];

        $data = [];

        if (Request::has('companies')) {
            $data = ClientCompetence::select(
                'statistic_competence_id',
                DB::raw('cast(avg(average_rating) as decimal(3, 2)) as averageRating'),
                DB::raw('cast(avg(CASE WHEN '.config('database.connections.mysql.prefix').'statistic_clients.type <> "self" THEN average_rating ELSE NULL END) as decimal(3, 2)) as averageRatingWithoutSelf')
            )
                ->join('statistic_clients', 'statistic_client_competences.statistic_client_id', '=',
                    'statistic_clients.id')
                ->whereHas('client.result', function (Builder $query) {
                    $query->whereHas('rating', function (Builder $query) {
                        $query->whereNot('status', 'draft');
                    })
                        ->when(Request::input('year'), function (Builder $query, string $year) {
                            $query->whereYear('created_at', $year);
                        })
                        ->whereIn('company_id', Request::input('companies'));
                })
                ->with('competence')
                ->groupBy('statistic_competence_id')
                ->get()
                ->map(function (ClientCompetence $item) {
                    return [
                        'competence' => $item->competence->name,
                        'averageRating' => $item->averageRating,
                        'averageRatingWithoutSelf' => $item->averageRatingWithoutSelf,
                    ];
                })
                ->toArray();
        }

        return [
            'columns' => $columns,
            'data' => $data
        ];
    }

    private function getFormFields(): array
    {
        $years = Result::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year')
            ->get()
            ->map(fn(Result $result) => [
                'value' => (string) $result->year,
                'label' => $result->year.' год',
            ]);

        $companies = Company::select('id', 'name')
            ->distinct()
            ->orderBy('name')
            ->get()
            ->map(fn(Company $company) => [
                'value' => (string) $company->id,
                'label' => $company->name,
            ]);

        return [
            [
                'label' => 'Год',
                'name' => 'year',
                'type' => 'select',
                'data' => $years
            ],
            [
                'label' => 'Компания',
                'name' => 'companies',
                'type' => 'multiselect',
                'data' => $companies
            ]
        ];
    }
}
