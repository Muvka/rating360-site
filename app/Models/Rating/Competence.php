<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competence extends Model
{
    protected $table = 'rating_competences';

    protected $guarded = [];

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(Template::class, 'rating_competence_template', 'rating_competence_id', 'rating_template_id');
    }

    public function markers(): HasMany
    {
        return $this->hasMany(CompetenceMarker::class, 'rating_competence_id');
    }
}
