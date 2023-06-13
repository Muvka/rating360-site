<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competence extends Model
{
    use SoftDeletes;

    protected $table = 'rating_competences';

    protected $guarded = [];

    public function templates(): BelongsToMany {
        return $this->belongsToMany(Template::class, 'rating_competence_template', 'rating_competence_id','rating_template_id')
            ->withTimestamps();
    }

    public function templateMarkers(): HasManyThrough {
        return $this->hasManyThrough(TemplateMarker::class, Template::class, 'rating_template_id', 'rating_competence_id');
    }
}
