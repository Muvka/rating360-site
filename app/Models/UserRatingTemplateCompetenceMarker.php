<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRatingTemplateCompetenceMarker extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function competence(): BelongsTo {
        return $this->belongsTo(UserRatingTemplateCompetence::class, 'user_rating_template_competence_id');
    }
}
