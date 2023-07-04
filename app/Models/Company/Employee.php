<?php

namespace App\Models\Company;

use App\Models\Shared\City;
use App\Models\Shared\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $table = 'company_employees';

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
        return $this->belongsTo(Division::class, 'company_division_id');
    }

    public function subdivision(): BelongsTo
    {
        return $this->belongsTo(Subdivision::class, 'company_subdivision_id');
    }

    public function directions(): BelongsToMany
    {
        return $this->belongsToMany(Direction::class, 'company_direction_employee', 'company_employee_id', 'company_direction_id')
            ->withTimestamps();
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(EmployeePosition::class, 'company_employee_position_id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(EmployeeLevel::class, 'company_employee_level_id');
    }

    public function directManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'direct_manager_id');
    }

    public function functionalManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'functional_manager_id');
    }

    public function directSubordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'direct_manager_id', 'id');
    }

    public function functionalSubordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'functional_manager_id', 'id');
    }

    public function managerAccess(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'company_manager_access', 'manager_id', 'employee_id')
            ->withTimestamps();
    }

    public function isManager(): bool
    {
        return $this->level || $this->level !== '5';
    }
}
