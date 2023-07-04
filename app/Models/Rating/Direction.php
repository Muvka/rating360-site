<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Direction extends Model
{
    protected $guarded = [];

    protected $table = 'rating_directions';

    public function results(): BelongsToMany {
        return $this->belongsToMany(Result::class, 'rating_direction_result', 'rating_direction_id', 'rating_result_id')
            ->withTimestamps();
    }
}
