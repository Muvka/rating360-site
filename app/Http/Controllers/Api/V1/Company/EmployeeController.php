<?php

namespace App\Http\Controllers\Api\V1\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\EmployeeStoreRequest;
use App\Http\Requests\Company\EmployeeUpdateRequest;
use App\Http\Resources\Company\ManagerResource;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EmployeeController extends Controller
{
    public function store(EmployeeStoreRequest $request): JsonResponse
    {
        $employee = Employee::create($request->validated());

        if ($request->has('company_direction_ids')) {
            $employee->directions()->sync($request->get('company_direction_ids'));
        }

        return response()->json([
            'message' => __('messages.company.employee.store.success'),
            'data' => $employee,
        ], 201);
    }

    public function update(Employee $employee, EmployeeUpdateRequest $request): JsonResponse
    {
        $employee->update($request->validated());

        if ($request->has('company_direction_ids')) {
            $employee->directions()->sync($request->get('company_direction_ids'));
        } else {
            $employee->directions()->detach();
        }

        $employee->refresh();

        return response()->json([
            'message' => __('messages.company.employee.update.success'),
            'data' => $employee,
        ]);
    }

    public function destroy(Employee $employee): JsonResponse
    {
        $employee->delete();

        return response()->json([
            'message' => __('messages.company.employee.destroy.success'),
        ], 204);
    }

    public function managers(): ResourceCollection
    {
        $managers = Employee::with([
            'city',
            'company',
            'division',
            'subdivision',
            'directions',
            'position',
            'level',
        ])
            ->whereHas('level', function (Builder $query) {
                $query->where('is_manager', true);
            })
            ->get();

        return ManagerResource::collection($managers);
    }
}
