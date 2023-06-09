<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateCompetenceMarker extends Model
{
    protected $table = 'rating_template_competence_markers';

    protected $guarded = [];

    public $timestamps = false;

    public function competence(): BelongsTo {
        return $this->belongsTo(TemplateCompetence::class, 'rating_template_competence_id');
    }
}
