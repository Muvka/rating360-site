<?php

namespace App\Http\Requests\Company;

use App\Rules\Company\EmployeeIsManager;

class EmployeeUpdateRequest extends EmployeeManagementBaseRequest
{
    public function rules(): array
    {
        return [
            'source_id' => 'nullable|integer|unique:App\Models\Company\Employee,source_id',
            'first_name' => 'nullable|string|max:64',
            'last_name' => 'nullable|string|max:64',
            'middle_name' => 'nullable|string|max:64',
            'email' => 'nullable|email|max:255|unique:App\Models\Company\Employee,email',
            'direct_manager_id' => ['nullable', 'integer', 'exists:App\Models\Company\Employee,id', new EmployeeIsManager()],
            'direct_manager_email' => ['nullable', 'email', 'exists:App\Models\Company\Employee,email', new EmployeeIsManager()],
            'functional_manager_id' => ['nullable', 'integer', 'exists:App\Models\Company\Employee,id', new EmployeeIsManager()],
            'functional_manager_email' => ['nullable', 'email', 'exists:App\Models\Company\Employee,email', new EmployeeIsManager()],
            'city_id' => 'nullable|integer|exists:App\Models\Shared\City,id',
            'company_id' => 'nullable|integer|exists:App\Models\Company\Company,id',
            'company_division_id' => 'nullable|integer|exists:App\Models\Company\Division,id',
            'company_subdivision_id' => 'nullable|integer|exists:App\Models\Company\Subdivision,id',
            'company_level_id' => 'nullable|integer|exists:App\Models\Company\Level,id',
            'company_position_id' => 'nullable|integer|exists:App\Models\Company\Position,id',
            'company_direction_ids' => 'nullable|array|exists:App\Models\Company\Direction,id',
        ];
    }
}
