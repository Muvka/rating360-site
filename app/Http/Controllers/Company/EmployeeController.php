<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company\Employee;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class EmployeeController extends Controller
{
    public function autocomplete(): JsonResponse
    {
        $employees = Employee::query()
            ->select(['id', 'full_name', 'deleted_at'])
            ->when(Request::input('search'), function (Builder $query, string $search) {
                $query->where('full_name', 'like', '%'.$search.'%');
            })
            ->limit(30)
            ->orderBy('full_name')
            ->get()
            ->map(function (Employee $employee) {
                return [
                    'value' => (string) $employee->id,
                    'label' => $employee->full_name,
                ];
            });

        return response()->json($employees);
    }
}
