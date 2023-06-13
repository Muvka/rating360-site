<?php

namespace App\Models\Shared;

use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $guarded = [];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'city_id');
    }
}
