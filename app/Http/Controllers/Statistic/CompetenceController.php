<?php

namespace App\Http\Controllers\Statistic;

use App\Exports\Statistic\StatisticExport;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Company\Direction;
use App\Models\Company\Division;
use App\Models\Company\Level;
use App\Models\Company\Position;
use App\Models\Company\Subdivision;
use App\Models\Rating\Competence;
use App\Models\Shared\City;
use App\Models\Statistic\Result;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CompetenceController extends Controller
{
    public function index(): Response
    {
        $filters = Request::only(['city', 'company', 'division', 'subdivision', 'direction', 'level', 'position', 'self']);

        return Inertia::render('Statistic/CompetencePage', [
            'title' => 'Статистика по компетенциям',
            'formData' => $this->getForm(),
            'filters' => $filters,
            'statistic' => $this->getStatistic(),
            'exportUrl' => route('client.statistic.competence.export', $filters)
        ]);
    }

    public function export(): BinaryFileResponse
    {

        $fileName = 'Статистика-по-компетенциям-';

        if (Request::has('self')) {
            $fileName .= '(с-самооценкой)';
        } else {
            $fileName .= '(без-самооценки)';
        }

        $fileName .= Carbon::now()->format('Y-m-d').'.xlsx';

        return Excel::download(new StatisticExport($this->getStatistic()), $fileName);
    }

    private function getStatistic(): array
    {
        $dbPrefix = config('database.connections.mysql.prefix');

        if (Request::input('self')) {
            $averageRatingSelect = DB::raw('cast(avg('.$dbPrefix.'statistic_client_competences.average_rating) as decimal(3, 2)) as averageRating');
        } else {
            $averageRatingSelect = DB::raw('cast(avg(CASE WHEN '.$dbPrefix.'statistic_clients.type <> "self" THEN '.$dbPrefix.'statistic_client_competences.average_rating ELSE NULL END) as decimal(3, 2)) as averageRating');
        }

        $directionCount = 0;
        $competenceIds = collect([]);
        $statistic = Result::select(
            DB::raw('ANY_VALUE('.$dbPrefix.'statistic_results.id) as id'),
            'statistic_results.company_employee_id',
            'city_id',
            'company_id',
            'company_division_id',
            'company_subdivision_id',
            'company_level_id',
            'company_position_id',
            'statistic_competence_id',
            DB::raw('(SELECT GROUP_CONCAT('.$dbPrefix.'company_directions.id) FROM '.$dbPrefix.'company_directions INNER JOIN '.$dbPrefix.'statistic_direction_result ON '.$dbPrefix.'company_directions.id = '.$dbPrefix.'statistic_direction_result.company_direction_id WHERE '.$dbPrefix.'statistic_direction_result.statistic_result_id = '.$dbPrefix.'statistic_results.id) as company_direction_ids'),
            $averageRatingSelect
        )
            ->join('statistic_clients', 'statistic_results.id', '=',
                'statistic_clients.statistic_result_id')
            ->join('statistic_client_competences', 'statistic_clients.id', '=',
                'statistic_client_competences.statistic_client_id')
            ->join('statistic_competences', 'statistic_client_competences.statistic_competence_id', '=',
                'statistic_competences.id')
            ->with([
                'employee:id,full_name',
                'city:id,name',
                'company:id,name',
                'division:id,name',
                'subdivision:id,name',
                'level:id,name',
                'position:id,name',
                'directions:id,name',
            ])
            ->whereHas('rating', function (Builder $query) {
                $query->where('status', 'closed');
            })
            ->when(Request::input('city'), function (Builder $query, string $city) {
                return $query->where('city_id', $city);
            })
            ->when(Request::input('company'), function (Builder $query, string $company) {
                return $query->where('company_id', $company);
            })
            ->when(Request::input('division'), function (Builder $query, string $division) {
                return $query->where('company_division_id', $division);
            })
            ->when(Request::input('subdivision'), function (Builder $query, string $subdivision) {
                return $query->where('company_subdivision_id', $subdivision);
            })
            ->when(Request::input('direction'), function (Builder $query, string $direction) {
                return $query->whereHas('directions', function (Builder $query) use ($direction) {
                    $query->where('company_direction_id', $direction);
                });
            })
            ->when(Request::input('level'), function (Builder $query, string $level) {
                return $query->where('company_level_id', $level);
            })
            ->when(Request::input('position'), function (Builder $query, string $position) {
                return $query->where('company_position_id', $position);
            })
            ->groupBy(
                'statistic_results.company_employee_id',
                'city_id',
                'company_id',
                'company_division_id',
                'company_subdivision_id',
                'company_level_id',
                'company_position_id',
                'company_direction_ids',
                'statistic_competence_id'
            )
            ->get()
            ->reduce(function (array $carry, Result $result) use (&$directionCount, $competenceIds) {
                $key = $result['company_employee_id'].'-'.$result['city_id'].'-'.$result['company_id'].'-'.$result['company_division_id'].'-'.$result['company_subdivision_id'].'-'.$result['company_level_id'].'-'.$result['company_position_id'].'-'.$result['company_direction_ids'];

                $directionCount = $result->directions->count() > $directionCount ? $result->directions->count() : $directionCount;
                $competenceIds->push($result->statistic_competence_id);

                if (isset($carry[$key])) {
                    $carry[$key]['competence-'.$result->statistic_competence_id] = $result->averageRating;
                } else {
                    $carry[$key] = [
                        'employee' => $result->employee->full_name,
                        'city' => $result->city?->name,
                        'company' => $result->company?->name,
                        'division' => $result->division?->name,
                        'subdivision' => $result->subdivision?->name,
                        'level' => $result->level?->name,
                        'position' => $result->position?->name,
                        ...$result->directions?->mapWithKeys(function (Direction $item, int $index) {
                            return ['direction-'.$index + 1 => $item->name];
                        }),
                        'competence-'.$result->statistic_competence_id => $result->averageRating,
                    ];
                }

                return $carry;
            }, []);

        $columns = [
            [
                'key' => 'employee',
                'label' => 'Сотрудник'
            ], [
                'key' => 'city',
                'label' => 'Город'
            ], [
                'key' => 'company',
                'label' => 'Компания'
            ], [
                'key' => 'division',
                'label' => 'Отдел'
            ], [
                'key' => 'subdivision',
                'label' => 'Подразделение'
            ], [
                'key' => 'position',
                'label' => 'Должность'
            ], [
                'key' => 'level',
                'label' => 'Уровень сотрудника'
            ]
        ];

        if ($directionCount > 0) {
            foreach (range(1, $directionCount) as $number) {
                $columns[] = [
                    'key' => 'direction-'.$number,
                    'label' => 'Направление'.($number > 1 ? ' '.$number : '')
                ];
            }
        }

        Competence::select('id', 'name')
            ->whereIn('id', $competenceIds->unique())
            ->get()
            ->each(function (Competence $item) use (&$columns) {
                $columns[] = [
                    'key' => 'competence-'.$item->id,
                    'label' => $item->name
                ];
            });

        return [
            'columns' => $columns,
            'data' => array_values($statistic),
        ];
    }

    private function getForm(): array
    {
        $cities = City::select('id', 'name')
            ->distinct()
            ->get()
            ->map(fn(City $city) => [
                'value' => (string) $city->id,
                'label' => $city->name,
            ]);

        $companies = Company::select('id', 'name')
            ->distinct()
            ->get()
            ->map(fn(Company $company) => [
                'value' => (string) $company->id,
                'label' => $company->name,
            ]);

        $divisions = Division::select('id', 'name')
            ->distinct()
            ->get()
            ->map(fn(Division $division) => [
                'value' => (string) $division->id,
                'label' => $division->name,
            ]);

        $subdivisions = Subdivision::select('id', 'name')
            ->distinct()
            ->get()
            ->map(fn(Subdivision $subdivision) => [
                'value' => (string) $subdivision->id,
                'label' => $subdivision->name,
            ]);

        $directions = Direction::select('id', 'name')
            ->distinct()
            ->get()
            ->map(fn(Direction $direction) => [
                'value' => (string) $direction->id,
                'label' => $direction->name,
            ]);

        $levels = Level::select('id', 'name')
            ->get()
            ->map(fn(Level $level) => [
                'value' => (string) $level->id,
                'label' => $level->name,
            ]);

        $positions = Position::select('id', 'name')
            ->distinct()
            ->get()
            ->map(fn(Position $position) => [
                'value' => (string) $position->id,
                'label' => $position->name,
            ]);

        return [
            'cities' => $cities,
            'companies' => $companies,
            'divisions' => $divisions,
            'subdivisions' => $subdivisions,
            'directions' => $directions,
            'levels' => $levels,
            'positions' => $positions,
            'self' => true,
        ];
    }
}
