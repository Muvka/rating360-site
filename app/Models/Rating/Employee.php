<?php

namespace App\Models\Rating;

use App\Models\Shared\City;
use App\Models\Shared\Company;
use App\Models\Shared\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $guarded = [];

    protected $table = 'rating_employees';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(EmployeeDivision::class, 'rating_employee_division_id');
    }

    public function subdivision(): BelongsTo
    {
        return $this->belongsTo(EmployeeSubdivision::class, 'rating_employee_subdivision_id');
    }

    public function directions(): BelongsToMany
    {
        return $this->belongsToMany(EmployeeDirection::class, 'rating_direction_employee', 'rating_employee_id','rating_employee_direction_id')
            ->withTimestamps();
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(EmployeePosition::class, 'rating_employee_position_id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(EmployeeLevel::class, 'rating_employee_level_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'direct_manager_id', 'id');
    }

    public function directManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'direct_manager_id');
    }

    public function functionalManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'functional_manager_id');
    }
}
