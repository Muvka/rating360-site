<?php

namespace App\Http\Controllers\Api\V1\Company;

use App\Http\Controllers\Controller;
use App\Http\Resources\Company\ManagerResource;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Builder;

class EmployeeController extends Controller
{
    public function managers()
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

        return response()->json(ManagerResource::collection($managers));
    }
}
