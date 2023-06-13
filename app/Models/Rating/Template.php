<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;

    protected $table = 'rating_templates';

    protected $guarded = [];

    public function ratings(): HasMany {
        return $this->hasMany(Rating::class, 'rating_template_id');
    }

    public function competences(): BelongsToMany {
        return $this->belongsToMany(Competence::class, 'rating_competence_template', 'rating_template_id','rating_competence_id')
            ->withTimestamps();
    }

    public function markers(): HasMany {
        return $this->hasMany(TemplateMarker::class, 'rating_template_id');
    }
}
