<?php

namespace App\Models\Statistic;

use App\Models\Company\Company;
use App\Models\Company\Direction;
use App\Models\Company\Division;
use App\Models\Company\Employee;
use App\Models\Company\Level;
use App\Models\Company\Position;
use App\Models\Company\Subdivision;
use App\Models\Rating\Rating;
use App\Models\Shared\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Result extends Model
{
    protected $table = 'statistic_results';

    protected $guarded = [];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'company_employee_id')
            ->withTrashed();
    }

    public function rating(): BelongsTo
    {
        return $this->belongsTo(Rating::class, 'rating_id')
            ->withTrashed();
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class)
            ->withTrashed();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)
            ->withTrashed();
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'company_division_id')
            ->withTrashed();
    }

    public function subdivision(): BelongsTo
    {
        return $this->belongsTo(Subdivision::class, 'company_subdivision_id')
            ->withTrashed();
    }

    public function directions(): BelongsToMany
    {
        return $this->belongsToMany(Direction::class, 'statistic_direction_result', 'statistic_result_id', 'company_direction_id')
            ->withTimestamps()
            ->withTrashed();
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'company_position_id')
            ->withTrashed();
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'company_level_id')
            ->withTrashed();
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'statistic_result_id');
    }
}
