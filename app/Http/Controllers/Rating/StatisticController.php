<?php

namespace App\Http\Controllers\Rating;

use App\Http\Controllers\Controller;
use App\Models\Rating\Direction;
use App\Models\Rating\Result;
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
        return Inertia::render('Rating/GeneralStatisticPage', [
            'title' => 'Общая статистика',
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
