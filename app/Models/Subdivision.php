<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subdivision extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function employees(): HasMany {
        return $this->hasMany(User::class, 'subdivision_id');
    }
}
