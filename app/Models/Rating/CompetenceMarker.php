<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetenceMarker extends Model
{
    protected $table = 'rating_competence_markers';

    protected $guarded = [];

    //    public function template(): BelongsTo {
    //        return $this->belongsTo(Template::class, 'rating_template_id');
    //    }

    public function competence(): BelongsTo
    {
        return $this->belongsTo(Competence::class, 'rating_competence_id');
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(Value::class, 'rating_value_id');
    }
}
