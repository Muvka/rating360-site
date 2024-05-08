<?php

namespace App\Rules\Company;

use App\Models\Company\Employee;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmployeeIsManager implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (in_array($attribute, ['direct_manager_email', 'functional_manager_email'])) {
            $attribute = 'email';
        } elseif (in_array($attribute, ['direct_manager_id', 'functional_manager_id'])) {
            $attribute = 'id';
        }

        $employee = Employee::where($attribute, $value)->first();

        if (! $employee) {
            $fail('Сотрудник не найден');
        } elseif (! $employee->level?->is_manager) {
            $fail('Сотрудник не является руководителем');
        }
    }
}
