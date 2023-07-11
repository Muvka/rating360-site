<?php

namespace App\Policies\Rating;

use App\Models\Company\Employee;
use App\Models\Rating\Rating;
use App\Models\Shared\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ResultPolicy
{
    public function viewAny(User $user): bool
    {
        return (bool) $user->employee?->isManager();
    }

    public function view(User $user, Employee $employee): bool
    {
        if ($user->employee?->id === $employee->id) {
            return true;
        }

        $managerId = Auth::user()?->employee?->id;

        // TODO: Переделать
        return (bool) Employee::whereHas('directManager', function (Builder $query) use ($managerId) {
            $query->where('id', $managerId);
        })
//            ->orWhereHas('managerAccess', function (Builder $query) use ($managerId) {
//                $query->where('laravel_reserved_1.id', $managerId);
//            })
            ->find($employee->id);
    }

    public function create(User $user, Rating $rating, Employee $employee): bool
    {
        if ($rating->status !== 'in progress') {
            return false;
        }

        $isActive = Rating::whereHas('matrixTemplates', function (Builder $query) use ($user, $employee) {
            $query->where('company_employee_id', $employee->id)
                ->whereHas('clients', function (Builder $query) use ($user) {
                    $query->where('company_employee_id', $user->employee?->id);
                });
        })
//            ->whereDoesntHave('results', function (Builder $query) use ($user, $employee) {
//                $query->where('company_employee_id', $employee->id)
//                    ->whereHas('clients', function (Builder $query) use ($user) {
//                        $query->where('company_employee_id', $user->employee?->id);
//                    });
//            })
            ->find($rating->id);

        return (bool) $isActive;
    }
}
