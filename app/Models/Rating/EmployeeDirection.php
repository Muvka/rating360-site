<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeDirection extends Model
{
    protected $guarded = [];

    protected $table = 'rating_employee_directions';

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'rating_employee_position_id');
    }
}
