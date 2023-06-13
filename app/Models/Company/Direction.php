<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Direction extends Model
{
    protected $guarded = [];

    protected $table = 'company_directions';

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'company_direction_id');
    }
}
