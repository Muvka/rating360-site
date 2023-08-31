<?php

namespace App\Policies\Statistic;

use App\Models\Company\Employee;
use App\Models\Rating\Rating;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ResultPolicy
{
    public function viewAny(Employee $user): bool
    {
        return (bool) $user->isManager();
    }

    public function view(Employee $user, Employee $employee): bool
    {
        if ($user->id === $employee->id) {
            return true;
        }

        return Employee::where(function (Builder $query) use ($user) {
            $query->where('direct_manager_id', $user->id)
                ->orWhere('functional_manager_id', $user->id);
        })
            ->find($employee->id) || Employee::whereHas('managerAccessRevert', function (Builder $query) use ($user) {
                $query->where('manager_id', $user->id);
            })->find($employee->id);
    }

    public function create(Employee $user, Rating $rating, Employee $employee): bool
    {
        if ($rating->status !== 'in progress') {
            return false;
        }

        $isActive = Rating::whereHas('matrixTemplates', function (Builder $query) use ($user, $employee) {
            $query->where('company_employee_id', $employee->id)
                ->whereHas('clients', function (Builder $query) use ($user) {
                    $query->where('company_employee_id', $user->id);
                });
        })
//            ->whereDoesntHave('results', function (Builder $query) use ($user, $employee) {
//                $query->where('company_employee_id', $employee->id)
//                    ->whereHas('clients', function (Builder $query) use ($user) {
//                        $query->where('company_employee_id', $user->id);
//                    });
//            })
            ->find($rating->id);

        return (bool) $isActive;
    }
}
