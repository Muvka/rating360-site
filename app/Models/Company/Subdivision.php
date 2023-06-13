<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subdivision extends Model
{
    protected $guarded = [];

    protected $table = 'company_subdivisions';

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'company_subdivision_id');
    }
}
