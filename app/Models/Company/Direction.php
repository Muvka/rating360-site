<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Direction extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $table = 'company_directions';

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'company_direction_employee', 'company_direction_id', 'company_employee_id')
            ->withTimestamps();
    }
}
