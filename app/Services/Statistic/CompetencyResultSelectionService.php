<?php

namespace App\Services\Statistic;

use App\Models\Company\Direction;
use App\Models\Statistic\Client;
use App\Models\Statistic\ClientCompetence;
use App\Models\Statistic\Competence;
use App\Models\Statistic\Result;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CompetencyResultSelectionService
{
    public function getFilteredResultsAsTable(Request $request, bool $withHref = false): array
    {
        $clientTableName = (new Client())->getTable();
        $clientCompetenceTableName = (new ClientCompetence())->getTable();
        $resultTableName = (new Result())->getTable();
        $competenceTableName = (new Competence())->getTable();
        $directionTableName = (new Direction())->getTable();
        $directionResultPivotTableName = (new Result())->directions()->getTable();

        if ($request->has('self')) {
            $averageRatingSelect = DB::raw("cast(avg($clientCompetenceTableName.average_rating) as decimal(3, 2)) as averageRating");
        } else {
            $averageRatingSelect = DB::raw("cast(avg(CASE WHEN $clientTableName.type <> 'self' THEN $clientCompetenceTableName.average_rating ELSE NULL END) as decimal(3, 2)) as averageRating");
        }

        $directionCount = 0;
        $competenceIds = new Collection();

        $statistic = Result::select(
            DB::raw("ANY_VALUE($resultTableName.id) as id"),
            "$resultTableName.company_employee_id",
            'city_id',
            'company_id',
            'company_division_id',
            'company_subdivision_id',
            'company_level_id',
            'company_position_id',
            'statistic_competence_id',
            DB::raw("(SELECT GROUP_CONCAT($directionTableName.id) FROM $directionTableName INNER JOIN $directionResultPivotTableName ON $directionTableName.id = $directionResultPivotTableName.company_direction_id WHERE $directionResultPivotTableName.statistic_result_id = $resultTableName.id) as company_direction_ids"),
            $averageRatingSelect
        )
            ->join($clientTableName, "$resultTableName.id", '=',
                "$clientTableName.statistic_result_id")
            ->join($clientCompetenceTableName, "$clientTableName.id", '=',
                "$clientCompetenceTableName.statistic_client_id")
            ->join($competenceTableName, "$clientCompetenceTableName.statistic_competence_id", '=',
                "$competenceTableName.id")
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
                $query->whereNot('status', 'draft');
            })
            ->when($request->input('year'), function (Builder $query, string $year) use ($resultTableName) {
                $query->whereYear("$resultTableName.created_at", $year);
            })
            ->when($request->input('employees'), function (Builder $query, array $employees) use ($resultTableName) {
                $query->whereIn("$resultTableName.company_employee_id", $employees);
            })
            ->when($request->input('city'), function (Builder $query, string $city) {
                $query->where('city_id', $city);
            })
            ->when($request->input('company'), function (Builder $query, string $company) {
                $query->where('company_id', $company);
            })
            ->when($request->input('division'), function (Builder $query, string $division) {
                $query->where('company_division_id', $division);
            })
            ->when($request->input('subdivision'), function (Builder $query, string $subdivision) {
                $query->where('company_subdivision_id', $subdivision);
            })
            ->when($request->input('direction'), function (Builder $query, string $direction) {
                $query->whereHas('directions', function (Builder $query) use ($direction) {
                    $query->where('company_direction_id', $direction);
                });
            })
            ->when($request->input('level'), function (Builder $query, string $level) {
                $query->where('company_level_id', $level);
            })
            ->when($request->input('position'), function (Builder $query, string $position) {
                $query->where('company_position_id', $position);
            })
            ->when($request->input('competences'), function (Builder $query, array $competences) use ($competenceTableName) {
                $query->whereIn("$competenceTableName.id", $competences);
            })
            ->groupBy(
                "$resultTableName.company_employee_id",
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
            ->reduce(function (array $carry, Result $result) use (&$directionCount, $competenceIds, $withHref) {
                $key = $result['company_employee_id'].'-'.$result['city_id'].'-'.$result['company_id'].'-'.$result['company_division_id'].'-'.$result['company_subdivision_id'].'-'.$result['company_level_id'].'-'.$result['company_position_id'].'-'.$result['company_direction_ids'];

                $directionCount = $result->directions->count() > $directionCount ? $result->directions->count() : $directionCount;
                $competenceIds->push($result->statistic_competence_id);

                if (isset($carry[$key])) {
                    $carry[$key]['competence-'.$result->statistic_competence_id] = $result->averageRating;
                } else {
                    $carry[$key] = [
                        'employee' => $withHref ? [
                            'text' => $result->employee->full_name,
                            'href' => route('client.statistic.results.show', $result->employee->id),
                        ] : $result->employee->full_name,
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
                'label' => 'Сотрудник',
            ], [
                'key' => 'city',
                'label' => 'Город',
            ], [
                'key' => 'company',
                'label' => 'Компания',
            ], [
                'key' => 'division',
                'label' => 'Отдел',
            ], [
                'key' => 'subdivision',
                'label' => 'Подразделение',
            ], [
                'key' => 'position',
                'label' => 'Должность',
            ], [
                'key' => 'level',
                'label' => 'Уровень сотрудника',
            ],
        ];

        if ($directionCount > 0) {
            foreach (range(1, $directionCount) as $number) {
                $columns[] = [
                    'key' => 'direction-'.$number,
                    'label' => 'Направление'.($number > 1 ? ' '.$number : ''),
                ];
            }
        }

        Competence::select('id', 'name')
            ->whereIn('id', $competenceIds->unique())
            ->get()
            ->each(function (Competence $item) use (&$columns) {
                $columns[] = [
                    'key' => 'competence-'.$item->id,
                    'label' => $item->name,
                ];
            });

        return [
            'columns' => $columns,
            'data' => array_values($statistic),
        ];
    }
}
