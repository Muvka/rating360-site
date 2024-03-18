<?php

namespace App\Http\Controllers\Statistic;

use App\Exports\Statistic\StatisticExport;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Company\Direction;
use App\Models\Company\Division;
use App\Models\Company\Employee;
use App\Models\Company\Level;
use App\Models\Company\Position;
use App\Models\Company\Subdivision;
use App\Models\Shared\City;
use App\Models\Statistic\Competence;
use App\Models\Statistic\Result;
use App\Services\Statistic\CompetencyResultSelectionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CompetenceController extends Controller
{
    public function index(Request $request, CompetencyResultSelectionService $competencyResultSelectionService): Response
    {
        $filters = $request->only(['year', 'city', 'company', 'division', 'subdivision', 'direction', 'level', 'position', 'employees', 'competences', 'self']);

        return Inertia::render('Statistic/StatisticPage', [
            'title' => 'Статистика по компетенциям',
            'fields' => $this->getFormFields($request),
            'filters' => $filters,
            'statistic' => $filters ? $competencyResultSelectionService->getFilteredResultsAsTable(request: $request, withHref: true) : [],
            'exportUrl' => route('client.statistic.competence.export', $filters),
        ]);
    }

    public function export(Request $request, CompetencyResultSelectionService $competencyResultSelectionService): BinaryFileResponse
    {

        $fileName = 'Статистика-по-компетенциям-';

        if ($request->has('self')) {
            $fileName .= '(с-самооценкой)';
        } else {
            $fileName .= '(без-самооценки)';
        }

        $fileName .= Carbon::now()->format('Y-m-d').'.xlsx';

        return Excel::download(new StatisticExport($competencyResultSelectionService->getFilteredResultsAsTable(request: $request)), $fileName);
    }

    private function getFormFields(Request $request): array
    {
        $years = Result::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year')
            ->get()
            ->map(fn (Result $result) => [
                'value' => (string) $result->year,
                'label' => $result->year.' год',
            ]);

        $cities = City::select('id', 'name')
            ->distinct()
            ->orderBy('name')
            ->get()
            ->map(fn (City $city) => [
                'value' => (string) $city->id,
                'label' => $city->name,
            ]);

        $companies = Company::select('id', 'name')
            ->distinct()
            ->orderBy('name')
            ->get()
            ->map(fn (Company $company) => [
                'value' => (string) $company->id,
                'label' => $company->name,
            ]);

        $divisions = Division::select('id', 'name')
            ->distinct()
            ->orderBy('name')
            ->get()
            ->map(fn (Division $division) => [
                'value' => (string) $division->id,
                'label' => $division->name,
            ]);

        $subdivisions = Subdivision::select('id', 'name')
            ->distinct()
            ->orderBy('name')
            ->get()
            ->map(fn (Subdivision $subdivision) => [
                'value' => (string) $subdivision->id,
                'label' => $subdivision->name,
            ]);

        $directions = Direction::select('id', 'name')
            ->distinct()
            ->orderBy('name')
            ->get()
            ->map(fn (Direction $direction) => [
                'value' => (string) $direction->id,
                'label' => $direction->name,
            ]);

        $levels = Level::select('id', 'name')
            ->orderBy('name')
            ->get()
            ->map(fn (Level $level) => [
                'value' => (string) $level->id,
                'label' => $level->name,
            ]);

        $positions = Position::select('id', 'name')
            ->distinct()
            ->orderBy('name')
            ->get()
            ->map(fn (Position $position) => [
                'value' => (string) $position->id,
                'label' => $position->name,
            ]);

        $competences = Competence::select('id', 'name')
            ->distinct()
            ->orderBy('name')
            ->get()
            ->map(fn (Competence $competences) => [
                'value' => (string) $competences->id,
                'label' => $competences->name,
            ]);

        return [
            [
                'label' => 'Год',
                'name' => 'year',
                'type' => 'select',
                'data' => $years,
            ],
            [
                'label' => 'Город',
                'name' => 'city',
                'type' => 'select',
                'data' => $cities,
            ],
            [
                'label' => 'Компания',
                'name' => 'company',
                'type' => 'select',
                'data' => $companies,
            ],
            [
                'label' => 'Отдел',
                'name' => 'division',
                'type' => 'select',
                'data' => $divisions,
            ],
            [
                'label' => 'Подразделение',
                'name' => 'subdivision',
                'type' => 'select',
                'data' => $subdivisions,
            ],
            [
                'label' => 'Направление',
                'name' => 'direction',
                'type' => 'select',
                'data' => $directions,
            ],
            [
                'label' => 'Уровень сотрудника',
                'name' => 'level',
                'type' => 'select',
                'data' => $levels,
            ],
            [
                'label' => 'Должность',
                'name' => 'position',
                'type' => 'select',
                'data' => $positions,
            ],
            [
                'label' => 'Компетенции',
                'name' => 'competences',
                'type' => 'multiselect',
                'data' => $competences,
            ],
            [
                'label' => 'Сотрудники',
                'name' => 'employees',
                'type' => 'async-select',
                'value' => Employee::findMany($request->input('employees'))
                    ->map(function (Employee $employee) {
                        return [
                            'value' => (string) $employee->id,
                            'label' => $employee->full_name,
                        ];
                    }),
            ],
            [
                'label' => 'С учетом самооценки',
                'name' => 'self',
                'type' => 'checkbox',
            ],
        ];
    }
}
