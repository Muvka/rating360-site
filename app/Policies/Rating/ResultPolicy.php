<?php

namespace App\Policies\Rating;

use App\Models\Company\Employee;
use App\Models\Rating\Rating;
use App\Models\Shared\User;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ResultPolicy
{
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
            ->whereDoesntHave('results', function (Builder $query) use ($user, $employee) {
                $query->where('company_employee_id', $employee->id)
                    ->whereHas('clients', function (Builder $query) use ($user) {
                        $query->where('company_employee_id', $user->employee?->id);
                    });
            })
            ->find($rating->id);

        return (bool)$isActive;
    }
}
