<?php

namespace App\Services;

use App\Models\Statistic\Marker;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CorporateValueDataService
{
    public function getAverageRatings(?array $filter = []): Collection
    {
        return Marker::select([
            'statistic_results.company_employee_id',
            DB::raw('YEAR(launched_at) as launched_year'),
            DB::raw('cast(avg(
                case
                    when rating >= 0 and rating < 3 then 0
                    when rating >= 3 and rating < 3.75 then 0.5
                    when rating >= 3.75 and rating <= 5 then 1
                end
            ) as decimal(3, 2)) as average_rating'),
        ])
            ->join('statistic_client_competences', 'statistic_client_competences.id', '=', 'statistic_markers.statistic_client_competence_id')
            ->join('statistic_clients', 'statistic_clients.id', '=', 'statistic_client_competences.statistic_client_id')
            ->join('statistic_results', 'statistic_results.id', '=', 'statistic_clients.statistic_result_id')
            ->join('ratings', 'ratings.id', '=', 'statistic_results.rating_id')
            ->when(! empty($filter['company_employee_id']), function (Builder $query) use ($filter) {
                $query->whereIn('statistic_results.company_employee_id', $filter['company_employee_id']);
            })
            ->whereNull('ratings.deleted_at')
            ->whereNotNull('rating_value_id')
            ->where(function (Builder $query) {
                $query->where('ratings.status', 'closed')
                    ->orWhere('ratings.show_results_before_completion', true);
            })
            ->oldest('launched_year')
            ->groupBy('statistic_results.company_employee_id', 'launched_year')
            ->get();
    }
}
