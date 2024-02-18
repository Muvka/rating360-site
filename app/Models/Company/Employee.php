<?php

namespace App\Models\Company;

use App\Models\Shared\City;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable implements FilamentUser, HasName
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'company_employees';

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'full_name',
        'email',
        'password',
        'direct_manager_id',
        'functional_manager_id',
        'city_id',
        'company_id',
        'company_division_id',
        'company_subdivision_id',
        'company_position_id',
        'company_level_id',
    ];

    protected $guarded = ['is_admin'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    public function getFilamentName(): string
    {
        return $this->full_name;
    }

    public function isAdmin()
    {
        return $this->is_admin;
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
        return $this->belongsTo(Position::class, 'company_position_id');
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'company_level_id');
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

    public function managerAccessRevert(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'company_manager_access', 'employee_id', 'manager_id')
            ->withTimestamps();
    }

    public function isManager(): bool
    {
        return (bool) $this->level?->is_manager;
    }
}
