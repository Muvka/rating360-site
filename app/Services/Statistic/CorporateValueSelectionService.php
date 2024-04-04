<?php

namespace App\Services\Statistic;

use App\Models\Company\Direction;
use App\Models\Rating\Rating;
use App\Models\Statistic\Client;
use App\Models\Statistic\ClientCompetence;
use App\Models\Statistic\Marker;
use App\Models\Statistic\Result;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CorporateValueSelectionService
{
    public function getLastYearAverageRating(?array $filter = []): Collection
    {
        $markerTableName = (new Marker())->getTable();
        $clientCompetenceTableName = (new ClientCompetence())->getTable();
        $clientTableName = (new Client())->getTable();
        $resultTableName = (new Result())->getTable();
        $ratingTableName = (new Rating())->getTable();

        return Marker::select([
            'type',
            "$resultTableName.company_employee_id",
            DB::raw('YEAR(launched_at) as launched_year'),
            DB::raw('cast(avg(rating) as decimal(3, 2)) as average_rating'),
        ])
            ->join($clientCompetenceTableName, "$clientCompetenceTableName.id", '=', "$markerTableName.statistic_client_competence_id")
            ->join($clientTableName, "$clientTableName.id", '=', "$clientCompetenceTableName.statistic_client_id")
            ->join($resultTableName, "$resultTableName.id", '=', "$clientTableName.statistic_result_id")
            ->join($ratingTableName, "$ratingTableName.id", '=', "$resultTableName.rating_id")
            ->when(! empty($filter['company_employee_id']), function (Builder $query) use ($filter, $resultTableName) {
                $query->whereIn("$resultTableName.company_employee_id", $filter['company_employee_id']);
            })
            ->whereNull("$ratingTableName.deleted_at")
            ->whereNotNull('rating_value_id')
            ->where(function (Builder $query) use ($ratingTableName) {
                $query->where("$ratingTableName.status", 'closed')
                    ->orWhere("$ratingTableName.show_results_before_completion", true);
            })
            ->oldest('launched_year')
            ->groupBy("$resultTableName.company_employee_id", 'type', 'launched_year')
            ->get()
            ->groupBy('company_employee_id')
            ->map(function (Collection $collection, int $companyEmployeeId) {
                return [
                    'company_employee_id' => $companyEmployeeId,
                    'launched_year' => $collection[0]['launched_year'],
                    'average_rating' => round($collection->filter(function (Marker $marker) {
                        return $marker->type !== 'self';
                    })->avg('average_rating'), 2),
                ];
            })
            ->values();
    }

    public function getFilteredResultsAsTable(Request $request, bool $withHref = false): array
    {
        $dbPrefix = config('database.connections.mysql.prefix');

        $directionCount = 0;
        $statistic = Result::select(
            DB::raw('ANY_VALUE('.$dbPrefix.'statistic_results.id) as id'),
            'statistic_results.company_employee_id',
            'type',
            'city_id',
            'company_id',
            'company_division_id',
            'company_subdivision_id',
            'company_level_id',
            'company_position_id',
            DB::raw('(SELECT GROUP_CONCAT('.$dbPrefix.'company_directions.id) FROM '.$dbPrefix.'company_directions INNER JOIN '.$dbPrefix.'statistic_direction_result ON '.$dbPrefix.'company_directions.id = '.$dbPrefix.'statistic_direction_result.company_direction_id WHERE '.$dbPrefix.'statistic_direction_result.statistic_result_id = '.$dbPrefix.'statistic_results.id) as company_direction_ids'),
            DB::raw('cast(avg('.$dbPrefix.'statistic_markers.rating) as decimal(3, 2)) as averageRating'),
        )
            ->when($request->input('value'), function (Builder $query) {
                $query->addSelect('rating_values.name as value');
            })
            ->join('statistic_clients', 'statistic_results.id', '=',
                'statistic_clients.statistic_result_id')
            ->join('statistic_client_competences', 'statistic_clients.id', '=',
                'statistic_client_competences.statistic_client_id')
            ->join('statistic_competences', 'statistic_client_competences.statistic_competence_id', '=',
                'statistic_competences.id')
            ->join('statistic_markers', 'statistic_markers.statistic_client_competence_id', '=',
                'statistic_client_competences.id')
            ->join('rating_values', 'statistic_markers.rating_value_id', '=',
                'rating_values.id')
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
            ->whereNotNull('statistic_markers.rating_value_id')
            ->when($request->input('year'), function (Builder $query, string $year) {
                $query->whereYear('statistic_results.created_at', $year);
            })
            ->when($request->input('employees'), function (Builder $query, array $employees) {
                $query->whereIn('statistic_results.company_employee_id', $employees);
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
            ->when($request->input('value'), function (Builder $query, string $value) {
                $query->where('rating_value_id', $value)
                    ->groupBy(
                        'statistic_results.company_employee_id',
                        'type',
                        'city_id',
                        'company_id',
                        'company_division_id',
                        'company_subdivision_id',
                        'company_level_id',
                        'company_position_id',
                        'company_direction_ids',
                        'value'
                    );
            }, function (Builder $query) {
                $query->groupBy(
                    'statistic_results.company_employee_id',
                    'type',
                    'city_id',
                    'company_id',
                    'company_division_id',
                    'company_subdivision_id',
                    'company_level_id',
                    'company_position_id',
                    'company_direction_ids',
                );
            })
            ->get()
            ->groupBy('company_employee_id')
            ->map(function (Collection $collection) use (&$directionCount, $withHref) {
                $result = $collection->first();
                $ratings = $collection->mapWithKeys(function ($item) {
                    return [$item['type'] => $item['averageRating']];
                });
                $directionCount = $result->directions->count() > $directionCount ? $result->directions->count() : $directionCount;

                return [
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
                    'value' => $result->value,
                    'averageRating' => round($ratings->avg(), 2),
                    'averageRatingWithoutSelf' => round($ratings->except('self')->avg(), 2),
                ];
            })
            ->sortBy([['value', 'asc'], ['employee', 'acs']])
            ->toArray();

        $directionColumns = [];

        if ($directionCount > 0) {
            foreach (range(1, $directionCount) as $number) {
                $directionColumns[] = [
                    'key' => 'direction-'.$number,
                    'label' => 'Направление'.($number > 1 ? ' '.$number : ''),
                ];
            }
        }

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
            ...$directionColumns,
        ];

        if ($request->input('value')) {
            $columns[] = [
                'key' => 'value',
                'label' => 'Ценность',
            ];
        }

        $columns = array_merge($columns, [
            [
                'key' => 'averageRating',
                'label' => 'Средняя оценка',
            ], [
                'key' => 'averageRatingWithoutSelf',
                'label' => 'Средняя оценка (без самооценки)',
            ],
        ]);

        return [
            'columns' => $columns,
            'data' => array_values($statistic),
        ];
    }
}
