<?php

namespace App\Models\Shared;

use App\Models\Rating\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $guarded = [];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'company_id');
    }
}
