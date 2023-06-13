<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competence extends Model
{
    use SoftDeletes;

    protected $table = 'rating_competences';

    protected $guarded = [];

//    public function markers(): HasMany {
//        return $this->hasMany(TemplateCompetenceMarker::class, 'rating_template_competence_id');
//    }

    public function templates(): BelongsToMany {
        return $this->belongsToMany(Template::class, 'rating_competence_template', 'rating_competence_id','rating_template_id')
            ->withTimestamps();
    }
}
