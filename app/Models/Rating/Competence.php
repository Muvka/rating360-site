<?php

namespace App\Models\Rating;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competence extends Model
{
    protected $table = 'rating_competences';

    protected $guarded = [];

    public function markers(): HasMany {
        return $this->hasMany(TemplateMarker::class, 'rating_competence_id');
    }
}
