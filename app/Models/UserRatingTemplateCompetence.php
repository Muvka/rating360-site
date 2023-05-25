<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRatingTemplateCompetence extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function markers(): HasMany {
        return $this->hasMany(UserRatingTemplateCompetenceMarker::class, 'user_rating_template_competence_id');
    }

    public function template(): BelongsTo {
        return $this->belongsTo(UserRatingTemplate::class, 'user_rating_template_id');
    }
}
