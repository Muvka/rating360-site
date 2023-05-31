<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRatingMatrixTemplateDirection extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function template(): BelongsTo {
        return $this->belongsTo(UserRatingMatrixTemplate::class, 'user_rating_matrix_template_id');
    }
}
