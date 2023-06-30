<?php

namespace App\Http\Controllers\Rating;

use App\Exports\Rating\ReportExport;
use App\Http\Controllers\Controller;
use App\Models\Company\Employee;
use App\Models\Rating\ResultClientMarker;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(): Response
    {
        $employee = Employee::select('id', 'user_id', 'company_id')
            ->with(
                'user:id,first_name,last_name,middle_name',
                'company:id,name'
            )
            ->findOrFail(Auth::user()?->employee?->id ?? 0);

        $results = ResultClientMarker::select(
            'rating_result_client_id',
            'competence',
            'text',
            'rating',
            'answer'
        )
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->whereHas('rating', function (Builder $query) {
                    $query->where('status', 'closed')
                        ->latest('launched_at');
                })
                    ->where('company_employee_id', $employee->id);
            })
            ->with('client:id,rating_result_id,type')
            ->whereNull('answer')
            ->get()
            ->groupBy('competence');

        $shortResults = $results->map(function (Collection $item, string $competence) {
            return collect([
                'competence' => $competence,
                'ratings' => $item->groupBy('client.type')->map(function (Collection $item) {
                    return round($item->avg('rating'), 1);
                })
            ]);
        })
            ->values()
            ->map(function (Collection $item) {
                $item['averageRating'] = round($item['ratings']->avg(), 1);
                $item['averageRatingWithoutSelf'] = round($item['ratings']->except('self')->avg(), 1);

                return $item;
            });

        $detailedResults = $results->map(function (Collection $item, string $competence) {
            return [
                'competence' => $competence,
                'markers' => $item->groupBy('text')->map(function (Collection $item, $marker) {
                    return [
                        'text' => $marker,
                        'ratings' => $item->groupBy('client.type')->map(function ($item) {
                            $rating = round($item->avg('rating'), 2);
                            return $rating ?: '?';
                        }),
                    ];
                })->values(),
            ];
        })
            ->values();

        $employeeFeedback = ResultClientMarker::select('id', 'text', 'answer')
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->where('company_employee_id', $employee->id);
            })
            ->whereNotNull('answer')
            ->get()
            ->groupBy('text');

        $companySummary = ResultClientMarker::select(
            'competence',
            DB::raw('cast(avg(rating) as decimal(3, 2)) as averageRating')
        )
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->where('company', $employee->company->name);
            })
            ->whereNotNull('rating')
            ->groupBy('competence')
            ->get();

        return Inertia::render('Rating/ReportPage', [
            'title' => 'Отчёт по оценке 360 - '.$employee->user->fullName,
            'companySummary' => $companySummary,
            'employeeFeedback' => $employeeFeedback,
            'shortResults' => $shortResults,
            'detailedResults' => $detailedResults,
        ]);
    }

    public function export()
    {
        $employee = Employee::select('id', 'user_id', 'company_id')
            ->with(
                'user:id,first_name,last_name,middle_name',
                'company:id,name'
            )
            ->findOrFail(Auth::user()?->employee?->id ?? 0);

        $fileName = sprintf('%s-%s.xlsx', $employee->user->fullName, $employee->company->name);

        $results = ResultClientMarker::select(
            'rating_result_client_id',
            'competence',
            'text',
            'rating',
            'answer'
        )
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->whereHas('rating', function (Builder $query) {
                    $query->where('status', 'closed')
                        ->latest('launched_at');
                })
                    ->where('company_employee_id', $employee->id);
            })
            ->with('client:id,rating_result_id,type')
            ->whereNull('answer')
            ->get()
            ->groupBy('competence');

        $shortResults = $results->map(function (Collection $item, string $competence) {
            return collect([
                'competence' => $competence,
                'ratings' => $item->groupBy('client.type')->map(function (Collection $item) {
                    return round($item->avg('rating'), 1);
                })
            ]);
        })
            ->values()
            ->map(function (Collection $item) {
                $item['averageRating'] = round($item['ratings']->avg(), 1);
                $item['averageRatingWithoutSelf'] = round($item['ratings']->except('self')->avg(), 1);

                return $item;
            });

        $detailedResults = $results->map(function (Collection $item, string $competence) {
            return [
                'competence' => $competence,
                'markers' => $item->groupBy('text')->map(function (Collection $item, $marker) {
                    return [
                        'text' => $marker,
                        'ratings' => $item->groupBy('client.type')->map(function ($item) {
                            $rating = round($item->avg('rating'), 2);
                            return $rating ?: '?';
                        }),
                    ];
                })->values(),
            ];
        })
            ->values();

        $employeeFeedback = ResultClientMarker::select('id', 'text', 'answer')
            ->whereHas('client.result', function (Builder $query) use ($employee) {
                $query->where('company_employee_id', $employee->id);
            })
            ->whereNotNull('answer')
            ->get()
            ->values()
            ->groupBy('text')
            ->flatten();

        $export = new ReportExport([
            'competences' => $shortResults->toArray(),
            'markers' => $detailedResults->toArray(),
            'feedback' => $employeeFeedback->toArray(),
        ]);

        return Excel::download($export, $fileName);
    }
}
