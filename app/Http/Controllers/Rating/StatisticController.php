<?php

namespace App\Http\Controllers\Rating;

use App\Http\Controllers\Controller;
use App\Models\Rating\Direction;
use App\Models\Rating\Result;
use App\Models\Rating\ResultClient;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Inertia\Response;
use Inertia\Inertia;

class StatisticController extends Controller
{
    public function __construct()
    {
        Inertia::share([
            'formData' => $this->getFormData(),
            'filters' => Request::only([
                'city',
                'company',
                'division',
                'subdivision',
                'direction',
                'level',
                'position',
                'self'
            ])
        ]);
    }

    public function general(): Response
    {
//        $employees = Result::whereHas('rating', function (Builder $query) {
//                $query->where('status', 'closed');
//            })
//            ->when(Request::input('city'), function (Builder $query, string $city) {
//                return $query->where('city', $city);
//            })
//            ->distinct()
//            ->get(['company_employee_id'])
//            ->pluck('company_employee_id');
//
//        dd($employees);
        $results = Result::selectRaw('id,rating_id,DISTINCT company_employee_id,city,company,division,subdivision,level,position')
            ->with('employee:id,full_name', 'directions')
            ->with([
                'clients' => function (Builder $query) {
                    $query->with([
                        'markers' => function (Builder $query) {
                            $query->select(
                                'rating_result_client_id',
                                DB::raw('cast(avg(rating) as decimal(3, 2)) as averageRating')
                            )
                                ->whereNotNull('rating')
                                ->groupBy('rating_result_client_id');
                        }
                    ]);
                }
            ])
            ->whereHas('rating', function (Builder $query) {
                $query->where('status', 'closed');
            })
            ->when(Request::input('city'), function (Builder $query, string $city) {
                return $query->where('city', $city);
            })
            ->when(Request::input('company'), function (Builder $query, string $company) {
                return $query->where('company', $company);
            })
            ->when(Request::input('division'), function (Builder $query, string $division) {
                return $query->where('division', $division);
            })
            ->when(Request::input('subdivision'), function (Builder $query, string $subdivision) {
                return $query->where('subdivision', $subdivision);
            })
            ->when(Request::input('direction'), function (Builder $query, string $direction) {
                return $query->whereHas('directions', function (Builder $query) use ($direction) {
                    $query->where('name', $direction);
                });
            })
            ->when(Request::input('level'), function (Builder $query, string $level) {
                return $query->where('level', $level);
            })
            ->when(Request::input('position'), function (Builder $query, string $position) {
                return $query->where('position', $position);
            })
            ->latest()
            ->distinct()
            ->get();
//            ->groupBy('company_employee_id')
//            ->map(function (Collection $results) {
//                return $results->map(function (Result $result) {
//                    return $result->clients->groupBy('type')->map(function (Collection $clients) {
//                        dd($clients);
//                        return round($clients->avg('average_markers_rating'), 1);
//                    });
//                });
//            });

        dd($results);

        return Inertia::render('Rating/GeneralStatisticPage', [
            'title' => 'Общая статистика',
            'results' => $results,
        ]);
    }

    public function competence(): Response
    {
        return Inertia::render('Rating/CompetenceStatisticPage', [
            'title' => 'Статистика по компетенциям',
        ]);
    }

    public function company(): Response
    {
        return Inertia::render('Rating/CompanyStatisticPage', [
            'title' => 'Статистика по компании',
        ]);
    }

    public function value(): Response
    {
        return Inertia::render('Rating/ValueStatisticPage', [
            'title' => 'Статистика по ценностям',
        ]);
    }

    private function getFormData(): array
    {
        $cities = Result::select('city')
            ->distinct()
            ->get()
            ->map(fn(Result $result) => [
                'value' => $result->city,
                'label' => $result->city,
            ])
            ->toArray();

        $companies = Result::select('company')
            ->distinct()
            ->get()
            ->map(fn(Result $result) => [
                'value' => $result->company,
                'label' => $result->company,
            ])
            ->toArray();

        $divisions = Result::select('division')
            ->distinct()
            ->get()
            ->map(fn(Result $result) => [
                'value' => $result->division,
                'label' => $result->division,
            ])
            ->toArray();

        $subdivisions = Result::select('subdivision')
            ->distinct()
            ->get()
            ->map(fn(Result $result) => [
                'value' => $result->subdivision,
                'label' => $result->subdivision,
            ])
            ->toArray();

        $directions = Direction::select('name')
            ->get()
            ->map(fn(Direction $direction) => [
                'value' => $direction->name,
                'label' => $direction->name,
            ])
            ->toArray();

        $levels = Result::select('level')
            ->distinct()
            ->get()
            ->map(fn(Result $result) => [
                'value' => $result->level,
                'label' => $result->level,
            ])
            ->toArray();

        $positions = Result::select('position')
            ->distinct()
            ->get()
            ->map(fn(Result $result) => [
                'value' => $result->position,
                'label' => $result->position,
            ])
            ->toArray();

        return [
            'cities' => $cities,
            'companies' => $companies,
            'divisions' => $divisions,
            'subdivisions' => $subdivisions,
            'directions' => $directions,
            'levels' => $levels,
            'positions' => $positions,
        ];
    }
}
