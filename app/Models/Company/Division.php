<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $table = 'company_divisions';

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'company_division_id');
    }
}
