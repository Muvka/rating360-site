<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Result extends Model
{
    use SoftDeletes;

    protected $table = 'rating_results';

    protected $guarded = [];

    public function rating(): BelongsTo {
        return $this->belongsTo(Rating::class, 'rating_id')
            ->withTrashed();
    }
}
