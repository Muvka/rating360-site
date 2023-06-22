<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultClient extends Model
{
    use SoftDeletes;

    protected $table = 'rating_result_clients';

    protected $guarded = [];

    public function result(): BelongsTo {
        return $this->belongsTo(Result::class, 'rating_result_id')
            ->withTrashed();
    }

    public function markers(): HasMany {
        return $this->hasMany(ResultClientMarker::class, 'rating_result_client_id');
    }
}
