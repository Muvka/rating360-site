<?php

namespace App\Http\Requests\Company;

use App\Models\Company\Employee;
use Illuminate\Foundation\Http\FormRequest;

class EmployeeManagementBaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return __('attributes.company.employee');
    }

    public function validated($key = null, $default = null)
    {
        $validatedData = parent::validated($key, $default);

        if (isset($validatedData['direct_manager_email'])) {
            $validatedData['direct_manager_id'] = Employee::where('email', $validatedData['direct_manager_email'])->first()?->id;

            unset($validatedData['direct_manager_email']);
        }

        if (isset($validatedData['functional_manager_email'])) {
            $validatedData['functional_manager_id'] = Employee::where('email', $validatedData['functional_manager_email'])->first()?->id;

            unset($validatedData['functional_manager_email']);
        }

        return $validatedData;
    }
}
