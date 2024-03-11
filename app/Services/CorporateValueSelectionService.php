<?php

namespace App\Services;

use App\Models\Rating\Rating;
use App\Models\Statistic\Client;
use App\Models\Statistic\ClientCompetence;
use App\Models\Statistic\Marker;
use App\Models\Statistic\Result;
use Illuminate\Contracts\Database\Eloquent\Builder;
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
                    'average_rating' => round($collection->avg('average_rating'), 2),
                ];
            })
            ->values();
    }
}
