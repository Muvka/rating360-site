<?php

namespace App\Policies\Statistic;

use App\Models\Company\Employee;
use App\Models\Rating\Rating;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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

        $managerId = Auth::user()?->id;

        // TODO: Переделать
        return (bool) Employee::whereHas('directManager', function (Builder $query) use ($managerId) {
            $query->where('id', $managerId);
        })
//            ->orWhereHas('managerAccess', function (Builder $query) use ($managerId) {
//                $query->where('laravel_reserved_1.id', $managerId);
//            })
            ->find($employee->id);
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
