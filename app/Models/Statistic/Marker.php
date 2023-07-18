<?php

namespace App\Models\Statistic;

use App\Casts\Statistic\MarkerRating;
use App\Models\Rating\Value;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Znck\Eloquent\Relations\BelongsToThrough;

class Marker extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $table = 'statistic_markers';

    protected $guarded = [];

    protected $casts = ['rating' => MarkerRating::class];

    public function clientCompetence(): BelongsTo
    {
        return $this->belongsTo(ClientCompetence::class, 'statistic_client_competence_id');
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(Value::class, 'rating_value_id');
    }

    public function client(): BelongsToThrough
    {
        return $this->belongsToThrough(Client::class, ClientCompetence::class, null, '', [
            ClientCompetence::class => 'statistic_client_competence_id'
        ]);
    }
}
