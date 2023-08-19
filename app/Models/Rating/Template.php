<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;

    protected $table = 'rating_templates';

    protected $guarded = [];

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'rating_template_id');
    }

    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'rating_competence_template', 'rating_template_id', 'rating_competence_id');
    }

//    public function markers(): BelongsToMany {
//        return $this->belongsToMany(CompetenceMarker::class, 'rating_competence_template', 'rating_competence_id', 'rating_template_id');
//    }
}
