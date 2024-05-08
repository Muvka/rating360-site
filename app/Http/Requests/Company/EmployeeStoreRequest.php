<?php

namespace App\Http\Requests\Company;

use App\Rules\Company\EmployeeIsManager;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return __('attributes.company.employee');
    }

    public function rules(): array
    {
        return [
            'source_id' => 'nullable|integer|unique:App\Models\Company\Employee,source_id',
            'first_name' => 'required|string|max:64',
            'last_name' => 'required|string|max:64',
            'middle_name' => 'nullable|string|max:64',
            'email' => 'required|email|max:255|unique:App\Models\Company\Employee,email',
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
