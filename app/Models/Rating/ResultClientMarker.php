<?php

namespace App\Models\Rating;

use App\Casts\Rating\ResultRating;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultClientMarker extends Model
{
    protected $table = 'rating_result_client_markers';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'rating' => ResultRating::class
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(ResultClient::class, 'rating_result_client_id');
    }
}
