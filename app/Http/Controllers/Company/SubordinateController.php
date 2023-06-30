<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class SubordinateController extends Controller
{
    public function index(): Response
    {
        $subordinates = Employee::with('user')
            ->where('direct_manager_id', Auth::user()?->employee?->id)
            ->get();

        return Inertia::render('Company/SubordinatesOverviewPage', [
            'title' => 'Результаты сотрудников',
            'subordinates' => $subordinates->map(function ($subordinate) {
                return [
                    'id' => $subordinate->id,
                    'name' => $subordinate->user->fullName,
                    'href' => route('client.company.subordinates.show', $subordinate->id),
                ];
            }),
        ]);
    }

    public function show(string $employeeId): Response
    {
        $employee = Employee::with('user')->findOrFail($employeeId);

        return Inertia::render('Rating/RatingReportPage', [
            'title' => 'Отчёт по оценке 360 - '.$employee->user->fullName
        ]);
    }
}
