<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateCompetence extends Model
{
    protected $table = 'rating_template_competences';

    protected $guarded = [];

    public $timestamps = false;

    public function markers(): HasMany {
        return $this->hasMany(TemplateCompetenceMarker::class, 'rating_template_competence_id');
    }

    public function template(): BelongsTo {
        return $this->belongsTo(Template::class, 'rating_template_id');
    }
}
