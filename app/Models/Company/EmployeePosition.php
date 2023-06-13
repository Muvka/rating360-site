<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeePosition extends Model
{
    protected $guarded = [];

    protected $table = 'company_employee_positions';

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'company_employee_position_id');
    }
}
